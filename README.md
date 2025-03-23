# Kurumsal Web Sitesi Projesi

Bu proje, kurumsal bir web sitesi uygulamasıdır. Dinamik içerik yönetimi sağlayan PHP tabanlı, responsive bir web sitesidir. Bootstrap framework'ü ile tasarlanmış ve veritabanı destekli bir yapıya sahiptir.

## Özellikler

- Responsive tasarım (tüm cihazlara uyumlu)
- Dinamik içerik yönetimi
- Admin paneli ile kolay içerik güncelleme
- SEO dostu yapı
- Hızlı yükleme süresi
- Güvenli veritabanı işlemleri (PDO)
- CSRF koruması
- Modern ve profesyonel görünüm

## Teknoloji Yığını

- **Backend**: PHP
- **Veritabanı**: MySQL (PDO kullanılarak)
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework'ler**: Bootstrap 5.3.3
- **Kütüphaneler**: Font Awesome 6.0, jQuery
- **Responsive Tasarım**: Tüm cihazlara uygun tasarım

## Kurulum

1. Projeyi bilgisayarınıza klonlayın veya indirin.
2. Dosyaları web sunucunuza yükleyin.
3. `database.sql` dosyasını kullanarak veritabanını oluşturun.
4. `config.php` dosyasını düzenleyerek veritabanı bağlantı bilgilerinizi girin.
5. Web tarayıcınızdan projeyi açın.

## Veritabanı Yapısı

Proje şu veritabanı tablolarını kullanmaktadır:

- `site_settings`: Site başlığı ve iletişim bilgileri
- `slider`: Ana sayfadaki slider içeriği
- `services`: Şirketin sunduğu hizmetlerin listesi
- `gallery`: Site galerisindeki medya içerikleri
- `referencess`: Şirketin referansları ve iş ortakları
- `contact_messages`: İletişim formundan gelen mesajlar
- `users`: Admin paneli kullanıcıları

## Dosya Yapısı

```
project/
│
├── assets/
│   ├── css/
│   │   └── style.css       # Ana stil dosyası
│   ├── js/
│   │   ├── menu.js         # Menü işlevleri için JavaScript
│   │   └── slider.js       # Slider işlevleri için JavaScript
│   └── img/                # Görsel dosyaları
│
├── uploads/                # Kullanıcı yüklenen dosyaların saklandığı klasör
│
├── includes/
│   ├── header.php          # Sayfa başlığı ve navigasyon
│   └── footer.php          # Sayfa alt bilgisi
│
├── pages/
│   ├── hizmetler.php       # Hizmetler sayfası/bölümü
│   ├── hizmet-detay.php    # Hizmet detay sayfası
│   ├── hakkimizda.php      # Hakkımızda sayfası/bölümü
│   └── ...                 # Diğer sayfalar
│
├── admin/                  # Yönetim paneli klasörü
│   ├── index.php           # Admin girişi ve dashboard
│   ├── services.php        # Hizmet yönetimi
│   ├── slider.php          # Slider yönetimi
│   ├── gallery.php         # Galeri yönetimi
│   ├── references.php      # Referanslar yönetimi
│   ├── settings.php        # Site ayarları
│   ├── messages.php        # İletişim mesajları
│   ├── users.php           # Kullanıcı yönetimi
│   └── includes/           # Admin panel için include dosyaları
│       ├── header.php      # Admin panel başlık/navigasyon
│       ├── footer.php      # Admin panel alt bilgi
│       └── auth.php        # Kimlik doğrulama
│
├── config.php              # Veritabanı ve site ayarları
├── README.md               # Proje dokümantasyonu
└── index.php               # Ana sayfa
```

## Bölümler ve İşlevler

### 1. Header (Üst Kısım)

- Logo ve site başlığı
- Navigasyon menüsü
- Mobil cihazlar için hamburger menü
- Üst bilgi çubuğu (iletişim bilgileri)

### 2. Slider Bölümü

- Tam genişlikte, otomatik geçişli dinamik slider
- Veritabanından dinamik olarak içerik çekme
- Responsive görüntü yönetimi

### 3. Hizmetler Bölümü

- Bootstrap Card bileşeni temelli tasarım
- Responsive grid sistem
- Veritabanından dinamik veri çekme

### 4. Hakkımızda Bölümü

- İki kolonlu responsive layout
- Şirket bilgileri, misyon ve vizyon
- İstatistik sayaçları

### 5. Galeri Bölümü

- Masonry grid layout
- Filtrelenebilir kategoriler
- Lightbox popup görüntüleme

### 6. Referanslar Bölümü

- Yatay kaydırmalı carousel düzeni
- Logo grid sistemi
- Veritabanından dinamik içerik çekme

### 7. İletişim Bölümü

- İki kolonlu responsive düzen
- İletişim formu
- Google Maps entegrasyonu

### 8. Footer (Alt Kısım)

- Çok kolonlu responsive grid düzeni
- Hızlı erişim linkleri
- İletişim bilgileri
- Telif hakkı bilgisi

### 9. Floating Butonlar

- WhatsApp iletişim butonu
- Telefon butonu
- Konum butonu
- Sayfa başına dön butonu

## Admin Paneli

Admin paneli, site içeriğinin dinamik olarak yönetilmesini sağlayan güvenli bir arayüzdür.

- Kullanıcı adı ve şifre ile güvenli giriş
- Rol tabanlı yetkilendirme (admin, editor, viewer)
- Dashboard ile genel istatistikler
- Slider, hizmetler, galeri, referanslar yönetimi
- İletişim mesajları yönetimi
- Kullanıcı yönetimi
- Site ayarları

## Güvenlik Özellikleri

- Password hashing
- PDO prepared statements ile SQL injection koruması
- XSS koruması
- CSRF token koruması
- Oturum güvenliği

## Geliştirici Notları

- Bootstrap framework'ü CSS override'lar ile özelleştirilmiştir
- Font Awesome ikonları UI elementleri için kullanılmaktadır
- Tüm içerikler veritabanından dinamik olarak çekildiği için CMS mantığında çalışmaktadır
- Admin paneli CRUD (Create, Read, Update, Delete) işlemleri için genel bir şablon kullanmaktadır
- Veritabanı işlemleri PDO üzerinden prepared statements kullanılarak yapılmaktadır

## Lisans

Bu proje [MIT lisansı](LICENSE) altında lisanslanmıştır.
