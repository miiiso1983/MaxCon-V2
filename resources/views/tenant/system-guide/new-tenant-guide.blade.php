@extends('layouts.modern')

@section('page-title', 'دليل المستأجر الجديد')
@section('page-description', 'دليل شامل للاستفادة القصوى من نظام MaxCon ERP')

@push('styles')
<style>
    .guide-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 30px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .guide-header::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .guide-header::after {
        content: '';
        position: absolute;
        bottom: -30px;
        left: -30px;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .progress-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .progress-bar {
        background: #e2e8f0;
        border-radius: 10px;
        height: 20px;
        overflow: hidden;
        margin-bottom: 15px;
    }

    .progress-fill {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        height: 100%;
        width: 0%;
        transition: width 0.3s ease;
        border-radius: 10px;
    }

    .step-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .step-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        border-color: #667eea;
    }

    .step-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .step-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 15px;
        font-size: 24px;
        color: white;
    }

    .step-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .step-description {
        color: #718096;
        font-size: 14px;
    }

    .step-time {
        background: #f7fafc;
        color: #4a5568;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-right: auto;
    }

    .task-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .task-item {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .task-item:last-child {
        border-bottom: none;
    }

    .task-checkbox {
        width: 20px;
        height: 20px;
        margin-left: 12px;
        accent-color: #667eea;
        cursor: pointer;
    }

    .task-text {
        flex: 1;
        color: #4a5568;
        font-size: 14px;
    }

    .task-item.completed .task-text {
        text-decoration: line-through;
        color: #a0aec0;
    }

    .checklist-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .checklist-header {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    .checklist-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 12px;
        font-size: 18px;
        color: white;
    }

    .timeline-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .week-card {
        margin-bottom: 25px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .week-header {
        padding: 20px;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
    }

    .day-item {
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
    }

    .day-item:last-child {
        border-bottom: none;
    }

    .day-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 10px;
    }

    .day-tasks {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .day-tasks li {
        color: #4a5568;
        font-size: 14px;
        margin-bottom: 5px;
        padding-right: 15px;
        position: relative;
    }

    .day-tasks li::before {
        content: "•";
        color: #667eea;
        font-weight: bold;
        position: absolute;
        right: 0;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 25px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5);
    }

    .btn-info {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    }

    .btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: #667eea;
        margin-bottom: 5px;
    }

    .stat-label {
        color: #718096;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .guide-header {
            padding: 25px;
        }
        
        .step-card {
            padding: 20px;
        }
        
        .step-header {
            flex-direction: column;
            text-align: center;
        }
        
        .step-icon {
            margin: 0 0 15px 0;
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .btn {
            width: 200px;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="guide-header">
        <div style="position: relative; z-index: 2;">
            <h1 style="margin: 0; font-size: 2.5rem; font-weight: 800; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                <i class="fas fa-rocket" style="margin-left: 15px;"></i>
                دليل المستأجر الجديد
            </h1>
            <p style="font-size: 1.2rem; margin: 15px 0 0 0; opacity: 0.9;">
                دليل شامل للاستفادة القصوى من نظام MaxCon ERP وتفعيل جميع الوحدات
            </p>
        </div>
    </div>

    <!-- Progress Section -->
    <div class="progress-section">
        <h3 style="margin-bottom: 20px; color: #2d3748; font-weight: 700;">
            <i class="fas fa-chart-line" style="color: #667eea; margin-left: 10px;"></i>
            تقدم التنفيذ
        </h3>
        
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill" style="width: 0%;"></div>
        </div>
        
        <div style="text-align: center; color: #4a5568; font-weight: 600;" id="progressText">
            0% مكتمل - ابدأ رحلتك مع MaxCon ERP
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid" style="margin-top: 25px;">
            <div class="stat-card">
                <div class="stat-number" id="completedTasks">0</div>
                <div class="stat-label">مهام مكتملة</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="totalTasks">{{ collect($checklist)->sum(function($section) { return count($section['items']); }) }}</div>
                <div class="stat-label">إجمالي المهام</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="currentWeek">1</div>
                <div class="stat-label">الأسبوع الحالي</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="daysLeft">30</div>
                <div class="stat-label">يوم متبقي</div>
            </div>
        </div>
    </div>

    <!-- Setup Steps -->
    <div style="margin-bottom: 30px;">
        <h2 style="color: #2d3748; font-weight: 700; margin-bottom: 25px; text-align: center;">
            <i class="fas fa-list-ol" style="color: #667eea; margin-left: 10px;"></i>
            خطوات الإعداد الأساسية
        </h2>
        
        @foreach($setupSteps as $step)
        <div class="step-card">
            <div class="step-header">
                <div class="step-icon" style="background: {{ $step['color'] }};">
                    <i class="{{ $step['icon'] }}"></i>
                </div>
                <div style="flex: 1;">
                    <div class="step-title">{{ $step['title'] }}</div>
                    <div class="step-description">{{ $step['description'] }}</div>
                </div>
                <div class="step-time">{{ $step['estimated_time'] }}</div>
            </div>
            
            <ul class="task-list">
                @foreach($step['tasks'] as $index => $task)
                <li class="task-item">
                    <input type="checkbox" class="task-checkbox" id="step{{ $step['id'] }}_task{{ $index }}" onchange="updateProgress()">
                    <label for="step{{ $step['id'] }}_task{{ $index }}" class="task-text">{{ $task }}</label>
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </div>

    <!-- Checklist Sections -->
    <div style="margin-bottom: 30px;">
        <h2 style="color: #2d3748; font-weight: 700; margin-bottom: 25px; text-align: center;">
            <i class="fas fa-check-square" style="color: #10b981; margin-left: 10px;"></i>
            قوائم المراجعة التفصيلية
        </h2>
        
        @foreach($checklist as $sectionKey => $section)
        <div class="checklist-section">
            <div class="checklist-header">
                <div class="checklist-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-tasks"></i>
                </div>
                {{ $section['title'] }}
            </div>
            
            <ul class="task-list">
                @foreach($section['items'] as $item)
                <li class="task-item {{ $item['completed'] ? 'completed' : '' }}">
                    <input type="checkbox" class="task-checkbox" id="{{ $item['id'] }}" {{ $item['completed'] ? 'checked' : '' }} onchange="updateProgress()">
                    <label for="{{ $item['id'] }}" class="task-text">{{ $item['text'] }}</label>
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </div>

    <!-- Implementation Timeline -->
    <div class="timeline-section">
        <h2 style="color: #2d3748; font-weight: 700; margin-bottom: 25px; text-align: center;">
            <i class="fas fa-calendar-alt" style="color: #f59e0b; margin-left: 10px;"></i>
            جدول التنفيذ (30 يوم)
        </h2>
        
        @foreach($timeline as $week)
        <div class="week-card">
            <div class="week-header" style="background: {{ $week['color'] }};">
                {{ $week['title'] }}
            </div>
            
            @foreach($week['days'] as $day)
            <div class="day-item">
                <div class="day-title">اليوم {{ $day['day'] }}: {{ $day['title'] }}</div>
                <ul class="day-tasks">
                    @foreach($day['tasks'] as $task)
                    <li>{{ $task }}</li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="/دليل_المستأجر_التفاعلي.html" target="_blank" class="btn btn-primary">
            <i class="fas fa-external-link-alt"></i>
            الدليل التفاعلي
        </a>
        <a href="/دليل_المستأجر_للطباعة.html" target="_blank" class="btn btn-info">
            <i class="fas fa-print"></i>
            نسخة للطباعة
        </a>
        <a href="{{ route('tenant.system-guide.videos') }}" class="btn btn-success">
            <i class="fas fa-play-circle"></i>
            الفيديوهات التعليمية
        </a>
    </div>
</div>

<script>
// Initialize progress tracking
let totalTasks = 0;
let completedTasks = 0;

// Count total tasks on page load
document.addEventListener('DOMContentLoaded', function() {
    totalTasks = document.querySelectorAll('input[type="checkbox"]').length;
    updateProgress();
    
    // Load saved progress from localStorage
    loadProgress();
});

function updateProgress() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    completedTasks = Array.from(checkboxes).filter(cb => cb.checked).length;
    
    const percentage = Math.round((completedTasks / totalTasks) * 100);
    
    // Update progress bar
    document.getElementById('progressFill').style.width = percentage + '%';
    document.getElementById('progressText').textContent = percentage + '% مكتمل';
    
    // Update stats
    document.getElementById('completedTasks').textContent = completedTasks;
    
    // Update current week based on progress
    const currentWeek = Math.ceil((completedTasks / totalTasks) * 4) || 1;
    document.getElementById('currentWeek').textContent = currentWeek;
    
    // Update days left
    const daysLeft = Math.max(0, 30 - Math.floor((completedTasks / totalTasks) * 30));
    document.getElementById('daysLeft').textContent = daysLeft;
    
    // Update visual state of completed tasks
    checkboxes.forEach(checkbox => {
        const taskItem = checkbox.closest('.task-item');
        if (checkbox.checked) {
            taskItem.classList.add('completed');
        } else {
            taskItem.classList.remove('completed');
        }
    });
    
    // Save progress to localStorage
    saveProgress();
    
    // Show congratulations if all tasks completed
    if (percentage === 100) {
        showCongratulations();
    }
}

function saveProgress() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const progress = {};
    
    checkboxes.forEach(checkbox => {
        progress[checkbox.id] = checkbox.checked;
    });
    
    localStorage.setItem('maxconNewTenantProgress', JSON.stringify(progress));
}

function loadProgress() {
    const savedProgress = localStorage.getItem('maxconNewTenantProgress');
    
    if (savedProgress) {
        const progress = JSON.parse(savedProgress);
        
        Object.keys(progress).forEach(checkboxId => {
            const checkbox = document.getElementById(checkboxId);
            if (checkbox) {
                checkbox.checked = progress[checkboxId];
            }
        });
        
        updateProgress();
    }
}

function showCongratulations() {
    // Simple congratulations alert
    setTimeout(() => {
        alert('🎉 تهانينا! لقد أكملت جميع خطوات إعداد النظام بنجاح. أنت الآن جاهز لاستخدام MaxCon ERP بكامل إمكانياته!');
    }, 500);
}
</script>
@endsection
