# Panduan Deploy Sistem Smart Parking IoT ke VPS (Virtual Private Server)

Dokumen ini menjelaskan seluruh komponen yang harus dipersiapkan, spesifikasi server, arsitektur deployment, serta langkah-langkah detail untuk mendeploy sistem **Smart Parking IoT** (Laravel 13 + Laravel Reverb + MySQL + ESP32 RFID + Mobile API) ke VPS Linux (Ubuntu Server).

---

## 🖥️ 1. Persiapan Infrastruktur & Spesifikasi VPS

Untuk menjalankan sistem ini dengan lancar, terutama karena menggunakan **WebSockets (Laravel Reverb)** untuk real-time update dan melayani request dari **ESP32** serta **Mobile Client**, berikut spesifikasi yang disarankan:

### Spesifikasi Server (Rekomendasi Minimal)
*   **OS:** Ubuntu Server 22.04 LTS atau 24.04 LTS (Sangat disarankan karena kestabilannya).
*   **CPU:** Minimal 1 vCPU (Disarankan 2 vCPU agar proses kompilasi asset Vite dan handling WebSockets lebih responsif).
*   **RAM:** Minimal 1 GB (Disarankan 2 GB jika Anda ingin melakukan build asset `npm run build` langsung di VPS).
*   **Storage:** 20 GB SSD/NVMe.
*   **IP Public:** Harus IP Public Statis (untuk konfigurasi domain, ESP32, dan mobile app).

### Domain & SSL (Wajib)
*   **Domain / Subdomain:** Misal `parking.domainanda.com` (diarahkan ke IP Public VPS menggunakan A Record).
*   **Mengapa Butuh Domain & SSL?**
    1.  **Keamanan API:** Mobile app (Android/iOS) dan ESP32 membutuhkan koneksi HTTPS yang aman agar token Sanctum tidak disadap.
    2.  **WebSockets (WSS):** Protokol WebSocket di production wajib menggunakan SSL (`wss://`) agar tidak diblokir oleh browser modern (karena browser yang diakses via HTTPS tidak diizinkan membuka koneksi WebSocket non-SSL `ws://`).

---

## 📦 2. Software Stack yang Harus Dipersiapkan di VPS

Aplikasi ini menggunakan teknologi terbaru (Laravel 13, PHP 8.2+, Vite, Reverb). Berikut software stack yang harus dipasang di VPS:

1.  **Web Server:** Nginx (Sangat direkomendasikan karena handal dalam melakukan *reverse proxy* untuk WebSocket Reverb).
2.  **Database:** MySQL Server 8.0+ atau MariaDB 10.6+.
3.  **PHP Engine:** PHP 8.2 atau PHP 8.3 dengan ekstensi berikut:
    *   `php-fpm`, `php-mysql`, `php-curl`, `php-mbstring`, `php-xml`, `php-bcmath`, `php-zip`, `php-gd`, `php-sqlite3` (optional).
4.  **Dependency Manager:**
    *   Composer (untuk library PHP).
    *   Node.js (v18+) & NPM (untuk compile frontend Vite).
5.  **Process Manager:** **Supervisor** (Wajib, digunakan untuk menjaga agar service `php artisan reverb:start` dan queue worker tetap berjalan di background dan otomatis restart jika mati).
6.  **SSL Provider:** Certbot (Let's Encrypt) untuk sertifikat SSL gratis.

---

## 🛠️ 3. Langkah-Langkah Instalasi & Deployment

### Langkah 1: Update Server & Install Firewall
Masuk ke VPS via SSH, lalu jalankan perintah berikut:
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install ufw git unzip curl -y

# Atur Firewall
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

### Langkah 2: Install PHP 8.2/8.3, Nginx, dan MySQL
Tambahkan repository PHP dan pasang paket yang diperlukan:
```bash
# Tambahkan PPA PHP
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP & Ekstensi
sudo apt install php8.2-fpm php8.2-cli php8.2-mysql php8.2-curl php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-zip php8.2-gd -y

# Install Nginx & MySQL
sudo apt install nginx mysql-server -y
```

### Langkah 3: Setup Database MySQL
Masuk ke MySQL dan buat database serta user baru:
```bash
sudo mysql
```
Di dalam console MySQL, jalankan query berikut:
```sql
CREATE DATABASE parking_sistem;
CREATE USER 'parking_user'@'localhost' IDENTIFIED BY 'PasswordKuatAnda123!';
GRANT ALL PRIVILEGES ON parking_sistem.* TO 'parking_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Langkah 4: Install Composer dan Node.js
```bash
# Install Composer secara global
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js (menggunakan NodeSource Node.js 20 LTS)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

### Langkah 5: Clone Project & Atur Folder Permission
Posisikan project Anda di `/var/www/`:
```bash
cd /var/www
sudo git clone https://github.com/username/ParkingSistem.git parking-sistem
cd parking-sistem

# Atur kepemilikan folder agar Nginx (www-data) bisa menulis ke storage
sudo chown -R www-data:www-data /var/www/parking-sistem
sudo chmod -R 775 /var/www/parking-sistem/storage
sudo chmod -R 775 /var/www/parking-sistem/bootstrap/cache
```

### Langkah 6: Konfigurasi File `.env` Produksi
Salin `.env.example` menjadi `.env` dan edit isinya:
```bash
cp .env.example .env
nano .env
```
Sesuaikan variabel berikut untuk production:
```ini
APP_NAME="Smart Parking IoT"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://parking.domainanda.com  # Ganti dengan domain Anda

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=parking_sistem
DB_USERNAME=parking_user
DB_PASSWORD=PasswordKuatAnda123!

# Gunakan Reverb untuk Broadcasting
BROADCAST_CONNECTION=reverb

# Reverb Configuration (Ubah host ke domain Anda)
REVERB_APP_ID=708938
REVERB_APP_KEY=olnqyyzbxmvpdvburjew
REVERB_APP_SECRET=ksmqpaaybayjqhxbzud8
REVERB_HOST="parking.domainanda.com"
REVERB_PORT=443                        # Hubungkan via port HTTPS standar
REVERB_SCHEME=https                    # Wajib HTTPS untuk production

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${VITE_REVERB_SCHEME}"

# Printer Configuration (Di VPS disarankan set 'disabled' atau 'file' untuk simulasi, detail lihat bagian 4)
PARKING_PRINT_MODE=disabled
```

### Langkah 7: Install Dependensi & Build Assets
```bash
# Install package PHP
composer install --no-dev --optimize-autoloader

# Generate Key & Run Migration
php artisan key:generate
php artisan migrate --force
php artisan storage:link

# Install npm & Build Asset frontend (Vite)
npm install
npm run build
```

---

## 🎛️ 4. Konfigurasi Supervisor untuk Laravel Reverb (WebSocket)

Karena Laravel Reverb bertindak sebagai WebSocket server independen, program ini harus terus berjalan di background. Kita menggunakan **Supervisor** untuk memantaunya.

1.  **Install Supervisor:**
    ```bash
    sudo apt install supervisor -y
    ```
2.  **Buat file konfigurasi Supervisor:**
    ```bash
    sudo nano /etc/supervisor/conf.d/parking-reverb.conf
    ```
3.  **Masukkan konfigurasi berikut:**
    ```ini
    [program:parking-reverb]
    process_name=%(program_name)s_%(process_num)02d
    command=php /var/www/parking-sistem/artisan reverb:start --host=127.0.0.1 --port=8080
    autostart=true
    autorestart=true
    user=www-data
    redirect_stderr=true
    stdout_logfile=/var/www/parking-sistem/storage/logs/reverb.log
    stopasgroup=true
    killasgroup=true
    ```
4.  **Update dan jalankan program:**
    ```bash
    sudo supervisorctl reread
    sudo supervisorctl update
    sudo supervisorctl start parking-reverb:*
    ```

---

## 🔀 5. Konfigurasi Nginx & SSL (Reverse Proxy)

Nginx akan menerima semua traffic dari port 80/443. Traffic biasa akan dialirkan ke PHP-FPM, sedangkan traffic WebSocket (`/app/*` atau `/reverb/*`) akan diteruskan ke port `8080` (Laravel Reverb).

1.  **Buat file konfigurasi Nginx:**
    ```bash
    sudo nano /etc/nginx/sites-available/parking-sistem
    ```
2.  **Masukkan konfigurasi Nginx berikut (Ganti `parking.domainanda.com` dengan domain Anda):**
    ```nginx
    server {
        listen 80;
        server_name parking.domainanda.com;
        root /var/www/parking-sistem/public;

        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-Content-Type-Options "nosniff";

        index index.php;
        charset utf-8;

        # Laravel App Main Configuration
        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }

        error_page 404 /index.php;

        # PHP-FPM Configuration
        location ~ \.php$ {
            fastcgi_pass unix:/var/run/php/php8.2-fpm.sock; # Sesuaikan dengan versi PHP Anda
            fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
            include fastcgi_params;
        }

        # Reverse Proxy for Laravel Reverb (WebSockets)
        location /app {
            proxy_pass http://127.0.0.1:8080;
            proxy_http_version 1.1;
            proxy_set_header Upgrade $http_upgrade;
            proxy_set_header Connection "Upgrade";
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto $scheme;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
    ```
3.  **Aktifkan konfigurasi & Restart Nginx:**
    ```bash
    sudo ln -s /etc/nginx/sites-available/parking-sistem /etc/nginx/sites-enabled/
    sudo nginx -t
    sudo systemctl restart nginx
    ```
4.  **Pasang SSL Let's Encrypt:**
    ```bash
    sudo apt install certbot python3-certbot-nginx -y
    sudo certbot --nginx -d parking.domainanda.com
    ```
    *Certbot akan otomatis memperbarui konfigurasi Nginx Anda untuk mendukung HTTPS (SSL).*

---

## 🔌 6. Konfigurasi IoT (ESP32) & Mobile Client ke VPS

Setelah server production Anda aktif dengan HTTPS (misal: `https://parking.domainanda.com`), Anda perlu menyesuaikan perangkat keras dan mobile client:

### A. Pada Kode ESP32 (Arduino IDE / VSCode PlatformIO)
1.  **Ganti URL API:**
    Ubah endpoint URL dari localhost ke domain server Anda:
    ```cpp
    const char* serverName = "https://parking.domainanda.com/api/scan";
    ```
2.  **Sertifikat SSL (HTTPS):**
    Karena server menggunakan HTTPS, ESP32 memerlukan sertifikat Root CA (Let's Encrypt) untuk memverifikasi SSL, atau gunakan instansiasi HTTPClient dengan metode `http.begin(client, serverName)` dan set insecure jika kesulitan (`client.setInsecure()`), namun demi keamanan produksi, menyematkan Root CA fingerprint sangat disarankan.

### B. Pada Mobile Client App (Flutter / React Native)
1.  **Base URL API:** Ubah konfigurasi API Client (seperti Axios atau Dio) menjadi:
    `https://parking.domainanda.com/api`
2.  **WebSocket Client (Echo/Pusher):**
    Sesuaikan inisialisasi Laravel Echo agar terhubung ke port HTTPS standard (`443`) dengan enkripsi diaktifkan:
    ```javascript
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: 'olnqyyzbxmvpdvburjew',
        wsHost: 'parking.domainanda.com',
        wsPort: 443,
        wssPort: 443,
        forceTLS: true,
        enabledTransports: ['ws', 'wss'],
    });
    ```

---

## 🖨️ 7. Catatan Penting Mengenai Thermal Printer di VPS

Ketika aplikasi ditaruh di VPS Cloud:
*   Variabel `.env` `PARKING_PRINT_MODE=windows` tidak bisa langsung mengakses printer fisik thermal yang terpasang via USB di komputer kasir lokal (karena server berada di Cloud VPS).
*   **Solusi Produksi:**
    1.  **Local Printing Agent (Rekomendasi):** Buat script background mini (Python/Node.js) di komputer kasir lokal yang melakukan polling secara real-time ke VPS (atau mendengarkan event Reverb WebSocket). Ketika ada data scan/transaksi baru, script lokal ini yang memerintahkan printer USB lokal untuk mencetak struk.
    2.  **Web-Based Printing:** Cetak tiket menggunakan Javascript langsung dari browser kasir dashboard dengan memanfaatkan driver print bawaan browser (Ctrl + P otomatis / Web Print API).
    3.  **Simulasi:** Untuk pengujian di VPS tanpa printer fisik, atur `.env` ke `PARKING_PRINT_MODE=file` untuk mencetak output struk dalam bentuk teks file di dalam folder storage server.
