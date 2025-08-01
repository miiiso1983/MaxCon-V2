# تطبيق نظام الفيديوهات التعليمية - دليل التطوير

## نظرة عامة

تم تطوير نظام شامل للفيديوهات التعليمية في MaxCon ERP يتضمن:
- عرض الفيديوهات بتصنيفات مختلفة
- بحث وفلترة متقدمة
- مشغل فيديو تفاعلي
- إحصائيات ومقاييس الأداء

## 🏗️ البنية التقنية

### 1. الملفات المُحدثة

#### Controller
```
app/Http/Controllers/Tenant/SystemGuideController.php
```
- تم تطوير دالة `getVideos()` لإرجاع بيانات الفيديوهات
- إضافة دوال مساعدة للفئات والإحصائيات
- دعم الفلترة حسب الوحدة

#### Views
```
resources/views/tenant/system-guide/videos-enhanced.blade.php
```
- واجهة محسنة مع تصميم عصري
- دعم البحث والفلترة
- مشغل فيديو منبثق
- عرض الإحصائيات

#### Routes
```
routes/tenant/system-guide.php
```
- راوت الفيديوهات مع دعم المعاملات الاختيارية

### 2. هيكل البيانات

#### بيانات الفيديو
```php
[
    'id' => 'unique-video-id',
    'title' => 'عنوان الفيديو',
    'description' => 'وصف مفصل',
    'duration' => '12:30',
    'difficulty' => 'مبتدئ|متوسط|متقدم',
    'thumbnail' => '/path/to/thumbnail.jpg',
    'video_url' => 'https://video-url.mp4',
    'category' => 'اسم الفئة',
    'tags' => ['كلمة1', 'كلمة2'],
    'views' => 1250,
    'rating' => 4.8,
    'created_at' => '2024-01-15',
    'updated_at' => '2024-01-15'
]
```

#### فئات الفيديوهات
```php
[
    'general' => ['name' => 'عام', 'icon' => 'fas fa-home', 'color' => '#667eea'],
    'sales' => ['name' => 'المبيعات', 'icon' => 'fas fa-shopping-bag', 'color' => '#10b981'],
    // ... باقي الفئات
]
```

## 🎥 إضافة فيديوهات جديدة

### 1. إضافة فيديو في الكود

```php
// في دالة getVideos() أو الدوال المساعدة
[
    'id' => 'new-video-id',
    'title' => 'عنوان الفيديو الجديد',
    'description' => 'وصف مفصل للفيديو',
    'duration' => '15:30',
    'difficulty' => 'متوسط',
    'thumbnail' => '/images/videos/new-video.jpg',
    'video_url' => 'https://example.com/videos/new-video.mp4',
    'embed_code' => null, // أو كود HTML للتضمين
    'category' => 'المبيعات',
    'tags' => ['مبيعات', 'فواتير', 'عملاء'],
    'views' => 0,
    'rating' => 0,
    'created_at' => now()->format('Y-m-d'),
    'updated_at' => now()->format('Y-m-d')
]
```

### 2. إضافة فيديو في JavaScript

```javascript
// في ملف videos-enhanced.blade.php
const videos = {
    'new-video-id': {
        title: 'عنوان الفيديو الجديد',
        description: 'وصف الفيديو',
        embedCode: '<iframe src="video-url"></iframe>' // أو HTML مخصص
    }
};
```

## 🔧 التخصيص والتطوير

### 1. إضافة منصة فيديو جديدة

#### YouTube
```php
'embed_code' => '<iframe width="100%" height="400" src="https://www.youtube.com/embed/VIDEO_ID" frameborder="0" allowfullscreen></iframe>'
```

#### Vimeo
```php
'embed_code' => '<iframe src="https://player.vimeo.com/video/VIDEO_ID" width="100%" height="400" frameborder="0" allowfullscreen></iframe>'
```

#### Wistia
```php
'embed_code' => '<iframe src="https://fast.wistia.net/embed/iframe/VIDEO_ID" width="100%" height="400" frameborder="0" allowfullscreen></iframe>'
```

### 2. إضافة ميزات جديدة

#### تتبع التقدم
```javascript
function trackVideoProgress(videoId, progress) {
    fetch('/api/videos/track-progress', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            video_id: videoId,
            progress: progress
        })
    });
}
```

#### تقييم الفيديوهات
```javascript
function rateVideo(videoId, rating) {
    fetch('/api/videos/rate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            video_id: videoId,
            rating: rating
        })
    });
}
```

## 📊 قاعدة البيانات (اختياري)

### إنشاء جداول الفيديوهات

```sql
-- جدول الفيديوهات
CREATE TABLE videos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    video_id VARCHAR(255) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    duration VARCHAR(10),
    difficulty ENUM('مبتدئ', 'متوسط', 'متقدم'),
    thumbnail VARCHAR(255),
    video_url VARCHAR(255),
    embed_code TEXT,
    category VARCHAR(100),
    tags JSON,
    views INT DEFAULT 0,
    rating DECIMAL(3,2) DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- جدول مشاهدات الفيديوهات
CREATE TABLE video_views (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    video_id VARCHAR(255),
    progress DECIMAL(5,2) DEFAULT 0, -- نسبة المشاهدة
    completed BOOLEAN DEFAULT FALSE,
    last_position INT DEFAULT 0, -- آخر موضع بالثواني
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (video_id) REFERENCES videos(video_id) ON DELETE CASCADE
);

-- جدول تقييمات الفيديوهات
CREATE TABLE video_ratings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    video_id VARCHAR(255),
    rating TINYINT CHECK (rating >= 1 AND rating <= 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_video (user_id, video_id),
    FOREIGN KEY (video_id) REFERENCES videos(video_id) ON DELETE CASCADE
);
```

### Models Laravel

```php
// app/Models/Video.php
class Video extends Model
{
    protected $fillable = [
        'video_id', 'title', 'description', 'duration', 'difficulty',
        'thumbnail', 'video_url', 'embed_code', 'category', 'tags',
        'views', 'rating', 'is_featured', 'is_active'
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function views()
    {
        return $this->hasMany(VideoView::class, 'video_id', 'video_id');
    }

    public function ratings()
    {
        return $this->hasMany(VideoRating::class, 'video_id', 'video_id');
    }
}
```

## 🚀 النشر والتطبيق

### 1. رفع الفيديوهات

#### خيار 1: استضافة خارجية (مُوصى به)
- YouTube (مجاني، سهل الاستخدام)
- Vimeo (جودة عالية، خيارات خصوصية)
- Wistia (احترافي، تحليلات متقدمة)

#### خيار 2: استضافة ذاتية
```php
// في config/filesystems.php
'videos' => [
    'driver' => 'local',
    'root' => storage_path('app/public/videos'),
    'url' => env('APP_URL').'/storage/videos',
    'visibility' => 'public',
],
```

### 2. تحسين الأداء

#### ضغط الفيديوهات
```bash
# باستخدام FFmpeg
ffmpeg -i input.mp4 -c:v libx264 -crf 23 -c:a aac -b:a 128k output.mp4
```

#### CDN للفيديوهات
```php
// في .env
VIDEO_CDN_URL=https://cdn.example.com/videos/
```

## 🔒 الأمان والخصوصية

### 1. حماية الفيديوهات
```php
// middleware للتحقق من الصلاحيات
public function handle($request, Closure $next)
{
    if (!auth()->user()->can('view-videos')) {
        abort(403);
    }
    return $next($request);
}
```

### 2. منع التحميل المباشر
```nginx
# في nginx
location /storage/videos/ {
    internal;
    alias /path/to/storage/app/public/videos/;
}
```

## 📈 التحليلات والمقاييس

### 1. تتبع المشاهدات
```javascript
// تتبع تلقائي للمشاهدات
video.addEventListener('timeupdate', function() {
    const progress = (video.currentTime / video.duration) * 100;
    if (progress > 90 && !completed) {
        markVideoCompleted(videoId);
        completed = true;
    }
});
```

### 2. تقارير الاستخدام
```php
// في Controller
public function getVideoAnalytics()
{
    return [
        'total_views' => VideoView::sum('views'),
        'completion_rate' => VideoView::where('completed', true)->count() / VideoView::count() * 100,
        'popular_videos' => Video::orderBy('views', 'desc')->take(10)->get(),
        'user_engagement' => VideoView::selectRaw('AVG(progress) as avg_progress')->first()
    ];
}
```

## 🛠️ الصيانة والتحديث

### 1. تحديث دوري للمحتوى
- مراجعة الفيديوهات كل 6 أشهر
- تحديث الروابط المعطلة
- إضافة فيديوهات للميزات الجديدة

### 2. مراقبة الأداء
```php
// في Command للمراقبة اليومية
public function handle()
{
    $brokenVideos = Video::whereNotNull('video_url')
        ->get()
        ->filter(function($video) {
            return !$this->checkVideoUrl($video->video_url);
        });

    if ($brokenVideos->count() > 0) {
        // إرسال تنبيه للمطورين
        Mail::to('admin@example.com')->send(new BrokenVideosAlert($brokenVideos));
    }
}
```

---

**ملاحظة:** هذا دليل تقني شامل لتطبيق نظام الفيديوهات. يمكن تخصيصه حسب متطلبات المشروع والبنية التحتية المتاحة.
