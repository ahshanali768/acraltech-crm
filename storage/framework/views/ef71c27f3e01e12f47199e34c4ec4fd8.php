<?php $__env->startSection('title', 'System Settings'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .settings-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .settings-tabs {
        border-bottom: 2px solid #e5e7eb;
        margin-bottom: 2rem;
    }
    
    .dark .settings-tabs {
        border-bottom-color: #374151;
    }
    
    .settings-tab {
        display: inline-flex;
        align-items: center;
        padding: 1rem 1.5rem;
        margin-right: 0.5rem;
        border-bottom: 3px solid transparent;
        color: #6b7280;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        background: none;
        border-left: none;
        border-right: none;
        border-top: none;
        border-radius: 0.5rem 0.5rem 0 0;
    }
    
    .settings-tab:hover {
        color: #3b82f6;
        background: rgba(59, 130, 246, 0.05);
        border-bottom-color: rgba(59, 130, 246, 0.3);
    }
    
    .settings-tab.active {
        color: #3b82f6;
        border-bottom-color: #3b82f6;
        background: rgba(59, 130, 246, 0.1);
    }
    
    .dark .settings-tab {
        color: #9ca3af;
    }
    
    .dark .settings-tab:hover {
        color: #60a5fa;
        background: rgba(96, 165, 250, 0.1);
    }
    
    .dark .settings-tab.active {
        color: #60a5fa;
        border-bottom-color: #60a5fa;
        background: rgba(96, 165, 250, 0.15);
    }
    
    .settings-content {
        min-height: 600px;
    }
    
    .settings-section {
        display: none;
    }
    
    .settings-section.active {
        display: block;
        animation: fadeInUp 0.4s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .dark .form-label {
        color: #d1d5db;
    }
    
    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        background: #ffffff;
        transition: all 0.2s ease;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .dark .form-input {
        background: #374151;
        border-color: #4b5563;
        color: #ffffff;
    }
    
    .dark .form-input:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-danger {
        background: #dc2626;
        color: white;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-danger:hover {
        background: #b91c1c;
    }
    
    .card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .dark .card {
        background: #1f2937;
        border-color: #374151;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .dark .section-title {
        color: #f9fafb;
    }
    
    .section-description {
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
    }
    
    .dark .section-description {
        color: #9ca3af;
    }
    
    /* Responsive tabs */
    @media (max-width: 768px) {
        .settings-tabs {
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
        
        .settings-tab {
            flex-shrink: 0;
            margin-right: 0.25rem;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="settings-container">
    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
        <div class="mb-6 bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>
    
    <?php if($errors->any()): ?>
        <div class="mb-6 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span class="font-medium">Please fix the following errors:</span>
            </div>
            <ul class="list-disc list-inside space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="text-sm"><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
            <i class="fas fa-cogs mr-3 text-blue-600"></i>
            System Settings
        </h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Configure and manage your CRM system preferences</p>
    </div>

    <!-- Settings Layout -->
    <div class="settings-container">
        <!-- Horizontal Settings Tabs -->
        <div class="settings-tabs flex flex-wrap">
            <button class="settings-tab active" data-tab="general">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                General
            </button>
            <button class="settings-tab" data-tab="attendance">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Attendance
            </button>
            <button class="settings-tab" data-tab="campaigns">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Campaigns
            </button>
            <button class="settings-tab" data-tab="users">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197"></path></svg>
                Users
            </button>
        </div>
        
        <div class="settings-content">
            <!-- General Settings -->
            <div id="general" class="settings-section active">
                <div class="card">
                    <h2 class="section-title">
                        <i class="fas fa-sliders-h text-blue-600"></i>
                        General Settings
                    </h2>
                    <p class="section-description">Configure basic system settings and preferences.</p>
                    
                    <form action="<?php echo e(route('admin.settings.general')); ?>" method="POST" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Lead Management Settings -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-users mr-2 text-blue-500"></i>
                                Lead Management
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Lead Auto-Assignment</label>
                                    <select name="lead_auto_assignment" class="form-input">
                                        <option value="disabled" <?php echo e(old('lead_auto_assignment', \App\Models\Setting::get('general.lead_auto_assignment', 'round_robin')) == 'disabled' ? 'selected' : ''); ?>>Disabled - Manual Assignment</option>
                                        <option value="round_robin" <?php echo e(old('lead_auto_assignment', \App\Models\Setting::get('general.lead_auto_assignment', 'round_robin')) == 'round_robin' ? 'selected' : ''); ?>>Round Robin - Equal Distribution</option>
                                        <option value="skill_based" <?php echo e(old('lead_auto_assignment', \App\Models\Setting::get('general.lead_auto_assignment', 'round_robin')) == 'skill_based' ? 'selected' : ''); ?>>Skill Based - By Vertical Experience</option>
                                        <option value="performance_based" <?php echo e(old('lead_auto_assignment', \App\Models\Setting::get('general.lead_auto_assignment', 'round_robin')) == 'performance_based' ? 'selected' : ''); ?>>Performance Based - Top Performers First</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">How new leads are automatically assigned to agents</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Duplicate Lead Detection</label>
                                    <select name="duplicate_detection" class="form-input">
                                        <option value="disabled" <?php echo e(old('duplicate_detection', \App\Models\Setting::get('general.duplicate_detection', 'phone_email')) == 'disabled' ? 'selected' : ''); ?>>Disabled</option>
                                        <option value="phone_only" <?php echo e(old('duplicate_detection', \App\Models\Setting::get('general.duplicate_detection', 'phone_email')) == 'phone_only' ? 'selected' : ''); ?>>Phone Number Only</option>
                                        <option value="email_only" <?php echo e(old('duplicate_detection', \App\Models\Setting::get('general.duplicate_detection', 'phone_email')) == 'email_only' ? 'selected' : ''); ?>>Email Only</option>
                                        <option value="phone_email" <?php echo e(old('duplicate_detection', \App\Models\Setting::get('general.duplicate_detection', 'phone_email')) == 'phone_email' ? 'selected' : ''); ?>>Phone + Email (Recommended)</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Prevent duplicate leads from being created</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Max Leads Per Agent (Daily)</label>
                                    <input type="number" name="max_leads_per_agent" min="0" max="500" class="form-input" 
                                           value="<?php echo e(old('max_leads_per_agent', \App\Models\Setting::get('general.max_leads_per_agent', 50))); ?>" 
                                           placeholder="50">
                                    <p class="text-xs text-gray-500 mt-1">Maximum leads assigned to one agent per day (0 = unlimited)</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Lead Follow-up Reminder</label>
                                    <select name="followup_reminder" class="form-input">
                                        <option value="disabled" <?php echo e(old('followup_reminder', \App\Models\Setting::get('general.followup_reminder', '1_hour')) == 'disabled' ? 'selected' : ''); ?>>Disabled</option>
                                        <option value="30_minutes" <?php echo e(old('followup_reminder', \App\Models\Setting::get('general.followup_reminder', '1_hour')) == '30_minutes' ? 'selected' : ''); ?>>30 Minutes</option>
                                        <option value="1_hour" <?php echo e(old('followup_reminder', \App\Models\Setting::get('general.followup_reminder', '1_hour')) == '1_hour' ? 'selected' : ''); ?>>1 Hour</option>
                                        <option value="4_hours" <?php echo e(old('followup_reminder', \App\Models\Setting::get('general.followup_reminder', '1_hour')) == '4_hours' ? 'selected' : ''); ?>>4 Hours</option>
                                        <option value="24_hours" <?php echo e(old('followup_reminder', \App\Models\Setting::get('general.followup_reminder', '1_hour')) == '24_hours' ? 'selected' : ''); ?>>24 Hours</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Automatic reminder for pending lead follow-ups</p>
                                </div>
                            </div>
                        </div>

                        <!-- Business Operations -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-business-time mr-2 text-green-500"></i>
                                Business Operations
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Default Country Code</label>
                                    <select name="default_country_code" class="form-input">
                                        <option value="+91" <?php echo e(old('default_country_code', \App\Models\Setting::get('general.default_country_code', '+91')) == '+91' ? 'selected' : ''); ?>>+91 (India)</option>
                                        <option value="+1" <?php echo e(old('default_country_code', \App\Models\Setting::get('general.default_country_code', '+91')) == '+1' ? 'selected' : ''); ?>>+1 (USA/Canada)</option>
                                        <option value="+44" <?php echo e(old('default_country_code', \App\Models\Setting::get('general.default_country_code', '+91')) == '+44' ? 'selected' : ''); ?>>+44 (UK)</option>
                                        <option value="+61" <?php echo e(old('default_country_code', \App\Models\Setting::get('general.default_country_code', '+91')) == '+61' ? 'selected' : ''); ?>>+61 (Australia)</option>
                                        <option value="+971" <?php echo e(old('default_country_code', \App\Models\Setting::get('general.default_country_code', '+91')) == '+971' ? 'selected' : ''); ?>>+971 (UAE)</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Default country code for phone number formatting</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Business Hours</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="time" name="business_hours_start" class="form-input" 
                                               value="<?php echo e(old('business_hours_start', \App\Models\Setting::get('general.business_hours_start', '09:00'))); ?>">
                                        <input type="time" name="business_hours_end" class="form-input" 
                                               value="<?php echo e(old('business_hours_end', \App\Models\Setting::get('general.business_hours_end', '18:00'))); ?>">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Operating hours for agent availability and reporting</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Lead Aging Alert (Days)</label>
                                    <select name="lead_aging_alert" class="form-input">
                                        <option value="0" <?php echo e(old('lead_aging_alert', \App\Models\Setting::get('general.lead_aging_alert', '3')) == '0' ? 'selected' : ''); ?>>Disabled</option>
                                        <option value="1" <?php echo e(old('lead_aging_alert', \App\Models\Setting::get('general.lead_aging_alert', '3')) == '1' ? 'selected' : ''); ?>>1 Day</option>
                                        <option value="3" <?php echo e(old('lead_aging_alert', \App\Models\Setting::get('general.lead_aging_alert', '3')) == '3' ? 'selected' : ''); ?>>3 Days</option>
                                        <option value="7" <?php echo e(old('lead_aging_alert', \App\Models\Setting::get('general.lead_aging_alert', '3')) == '7' ? 'selected' : ''); ?>>7 Days</option>
                                        <option value="14" <?php echo e(old('lead_aging_alert', \App\Models\Setting::get('general.lead_aging_alert', '3')) == '14' ? 'selected' : ''); ?>>14 Days</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Alert when leads haven't been contacted for X days</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Default Campaign Status</label>
                                    <select name="default_campaign_status" class="form-input">
                                        <option value="draft" <?php echo e(old('default_campaign_status', \App\Models\Setting::get('general.default_campaign_status', 'active')) == 'draft' ? 'selected' : ''); ?>>Draft - Manual Activation</option>
                                        <option value="active" <?php echo e(old('default_campaign_status', \App\Models\Setting::get('general.default_campaign_status', 'active')) == 'active' ? 'selected' : ''); ?>>Active - Ready for Leads</option>
                                        <option value="paused" <?php echo e(old('default_campaign_status', \App\Models\Setting::get('general.default_campaign_status', 'active')) == 'paused' ? 'selected' : ''); ?>>Paused - Review Required</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Default status for new campaigns</p>
                                </div>
                            </div>
                        </div>

                        <!-- Performance & Quality -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-chart-line mr-2 text-purple-500"></i>
                                Performance & Quality
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Daily Call Target (Per Agent)</label>
                                    <input type="number" name="daily_call_target" min="0" max="500" class="form-input" 
                                           value="<?php echo e(old('daily_call_target', \App\Models\Setting::get('general.daily_call_target', 100))); ?>" 
                                           placeholder="100">
                                    <p class="text-xs text-gray-500 mt-1">Target number of calls per agent per day</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Minimum Call Duration (Seconds)</label>
                                    <input type="number" name="min_call_duration" min="0" max="3600" class="form-input" 
                                           value="<?php echo e(old('min_call_duration', \App\Models\Setting::get('general.min_call_duration', 30))); ?>" 
                                           placeholder="30">
                                    <p class="text-xs text-gray-500 mt-1">Minimum call duration to count as valid call</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Quality Score Threshold</label>
                                    <select name="quality_threshold" class="form-input">
                                        <option value="6" <?php echo e(old('quality_threshold', \App\Models\Setting::get('general.quality_threshold', '7')) == '6' ? 'selected' : ''); ?>>6.0 - Basic Quality</option>
                                        <option value="7" <?php echo e(old('quality_threshold', \App\Models\Setting::get('general.quality_threshold', '7')) == '7' ? 'selected' : ''); ?>>7.0 - Good Quality</option>
                                        <option value="8" <?php echo e(old('quality_threshold', \App\Models\Setting::get('general.quality_threshold', '7')) == '8' ? 'selected' : ''); ?>>8.0 - High Quality</option>
                                        <option value="9" <?php echo e(old('quality_threshold', \App\Models\Setting::get('general.quality_threshold', '7')) == '9' ? 'selected' : ''); ?>>9.0 - Excellent Quality</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Minimum quality score for lead approval</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Auto-approval Conversion Rate (%)</label>
                                    <input type="number" name="auto_approval_rate" min="0" max="100" step="0.1" class="form-input" 
                                           value="<?php echo e(old('auto_approval_rate', \App\Models\Setting::get('general.auto_approval_rate', '5.0'))); ?>" 
                                           placeholder="5.0">
                                    <p class="text-xs text-gray-500 mt-1">Auto-approve agents with conversion rate above this threshold</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Save General Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Attendance Settings -->
            <div id="attendance" class="settings-section">
                <div class="card">
                    <h2 class="section-title">
                        <i class="fas fa-clock text-orange-600"></i>
                        Attendance Settings
                    </h2>
                    <p class="section-description">Configure attendance rules and location-based settings.</p>
                    
                    <form action="<?php echo e(route('admin.settings.attendance')); ?>" method="POST" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Work Hours Configuration -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-business-time mr-2 text-blue-500"></i>
                                Work Hours Configuration
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Default Work Hours (per day)</label>
                                    <input type="number" min="1" max="24" name="work_hours" class="form-input" value="<?php echo e(old('work_hours', 8)); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Attendance Reminder (minutes before shift)</label>
                                    <input type="number" min="0" max="120" name="attendance_reminder" class="form-input" value="<?php echo e(old('attendance_reminder', 15)); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Break Settings -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-coffee mr-2 text-green-500"></i>
                                Break Settings
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Max Breaks Allowed</label>
                                    <input type="number" min="0" max="10" name="max_breaks" class="form-input" value="<?php echo e(old('max_breaks', 3)); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Min Break Duration (minutes)</label>
                                    <input type="number" min="1" max="120" name="min_break_duration" class="form-input" value="<?php echo e(old('min_break_duration', 10)); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Location Management -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                    Allowed Locations
                                </h3>
                                <button type="button" onclick="document.getElementById('location-modal').classList.remove('hidden')" class="btn-secondary">
                                    <i class="fas fa-plus mr-1"></i>
                                    Add Location
                                </button>
                            </div>
                            
                            <div class="space-y-3">
                                <?php $__currentLoopData = \App\Models\AllowedLocation::orderBy('created_at', 'desc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white"><?php echo e($location->name); ?></div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                <?php echo e(ucfirst($location->location_type)); ?>: <?php echo e($location->address); ?>

                                            </div>
                                        </div>
                                        <form action="<?php echo e(route('admin.settings.delete_location', $location->id)); ?>" method="POST" onsubmit="return confirm('Delete this location?')" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(\App\Models\AllowedLocation::count() === 0): ?>
                                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-map-marker-alt text-4xl mb-4 opacity-50"></i>
                                            <p>No allowed locations added yet.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    Save Attendance Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Campaign Settings -->
                <div id="campaigns" class="settings-section">
                    <div class="card">
                        <h2 class="section-title">
                            <i class="fas fa-bullhorn text-red-600"></i>
                            Campaign Settings
                        </h2>
                        <p class="section-description">Configure campaign defaults and performance settings.</p>
                        
                        <form action="<?php echo e(route('admin.settings.campaign')); ?>" method="POST" class="space-y-6">
                            <?php echo csrf_field(); ?>
                            
                            <!-- Default Settings -->
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fas fa-cog mr-2 text-blue-500"></i>
                                    Default Campaign Settings
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label">Default Commission (INR)</label>
                                        <input type="number" step="0.01" min="0" name="default_commission_inr" class="form-input" placeholder="0.00">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Default Payout (USD)</label>
                                        <input type="number" step="0.01" min="0" name="default_payout_usd" class="form-input" placeholder="0.00">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Auto-approve Campaigns</label>
                                        <select name="auto_approve_campaigns" class="form-input">
                                            <option value="0">No - Manual Approval</option>
                                            <option value="1">Yes - Auto Approve</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Campaign Duration (days)</label>
                                        <input type="number" min="1" name="default_campaign_duration" class="form-input" placeholder="30">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Performance Settings -->
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fas fa-chart-line mr-2 text-green-500"></i>
                                    Performance Tracking
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label">Min Conversion Rate (%)</label>
                                        <input type="number" step="0.1" min="0" max="100" name="min_conversion_rate" class="form-input" placeholder="5.0">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Quality Score Threshold</label>
                                        <input type="number" step="0.1" min="0" max="10" name="quality_score_threshold" class="form-input" placeholder="7.0">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Call Duration Minimum (seconds)</label>
                                        <input type="number" min="0" name="min_call_duration" class="form-input" placeholder="30">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Enable Real-time Tracking</label>
                                        <select name="enable_realtime_tracking" class="form-input">
                                            <option value="1">Yes - Enable</option>
                                            <option value="0">No - Disable</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    Save Campaign Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- User Settings -->
                <div id="users" class="settings-section">
                    <?php echo $__env->make('admin.partials.user-management', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Adding Location -->
    <div id="location-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md p-6 m-4">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                    Add Allowed Location
                </h4>
                <button onclick="document.getElementById('location-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="<?php echo e(route('admin.settings.add_location')); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label">Location Name</label>
                    <input type="text" name="location_name" class="form-input" placeholder="e.g., Main Office, Home Office">
                </div>
                <div class="form-group">
                    <label class="form-label">Location Type</label>
                    <select name="location_type" class="form-input" onchange="updateLocationPlaceholder(this.value)">
                        <option value="address">Physical Address</option>
                        <option value="ip">IP Address</option>
                        <option value="geo">GPS Coordinates</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Location Value</label>
                    <div class="flex gap-2">
                        <input type="text" name="location_value" id="location_value" class="form-input" placeholder="Enter address, IP, or coordinates">
                        <button type="button" onclick="captureLocation()" class="btn-secondary whitespace-nowrap" id="capture-btn" style="display: none;">
                            <i class="fas fa-crosshairs mr-1"></i>
                            GPS
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Click GPS to use your current location for coordinates. 
                        <br><strong>Note:</strong> Geolocation requires HTTPS or localhost to work properly.
                        <br><button type="button" onclick="testGeolocation()" class="text-blue-500 underline">Test Geolocation</button>
                    </p>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="document.getElementById('location-modal').classList.add('hidden')" class="btn-secondary">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        Add Location
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Global functions for tab management - accessible from anywhere
function switchTab(tabName) {
    const tabs = document.querySelectorAll('.settings-tab');
    const sections = document.querySelectorAll('.settings-section');
    
    tabs.forEach(tab => {
        if (tab.dataset.tab === tabName) {
            tab.classList.add('active');
            loadTabData(tabName);
        } else {
            tab.classList.remove('active');
        }
    });

    sections.forEach(section => {
        section.classList.toggle('active', section.id === tabName);
    });
    
    // Update URL hash
    history.pushState(null, null, '#' + tabName);
}

function loadTabData(tabName) {
    const section = document.getElementById(tabName);
    // Check if content is already loaded or if it's a static section
    if (!section || (section.innerHTML.trim() !== '' && section.hasAttribute('data-ajax-loaded'))) {
        return;
    }

    let url = '';
    switch(tabName) {
        case 'attendance':
            url = '<?php echo e(route("admin.settings.partial.attendance")); ?>';
            break;
        case 'campaigns':
            url = '<?php echo e(route("admin.settings.partial.campaign")); ?>';
            break;
        case 'users':
            url = '<?php echo e(route("admin.settings.partial.user")); ?>';
            break;
        default:
            return; // No dynamic loading for the 'general' tab
    }

    // Show a loading indicator
    section.innerHTML = `<div class="text-center py-16"><i class="fas fa-spinner fa-spin text-4xl text-blue-500"></i><p class="mt-4 text-gray-500">Loading...</p></div>`;

    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(html => {
        section.innerHTML = html;
        section.setAttribute('data-ajax-loaded', 'true'); // Mark as loaded
        
        // Reinitialize functionality based on the loaded tab
        if (tabName === 'attendance') {
            initializeAttendanceTab();
        } else if (tabName === 'campaigns') {
            initializeCampaignsTab();
        }
    })
    .catch(error => {
        console.error('Error loading tab data:', error);
        section.innerHTML = `<div class="text-center py-16 text-red-500"><i class="fas fa-exclamation-triangle text-4xl"></i><p class="mt-4">Failed to load content. Please try again.</p></div>`;
    });
}

// Force reload tab content (ignores "already loaded" status)
function forceReloadTab(tabName) {
    const section = document.getElementById(tabName);
    if (!section) return;
    
    // Remove the "already loaded" marker to force reload
    section.removeAttribute('data-ajax-loaded');
    section.innerHTML = '';
    
    // Now load the tab normally
    loadTabData(tabName);
}

// Show success message
function showSuccessMessage(message) {
    // Create success notification
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Make functions globally accessible
window.switchTab = switchTab;
window.loadTabData = loadTabData;
window.forceReloadTab = forceReloadTab;
window.showSuccessMessage = showSuccessMessage;

document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.settings-tab');
    const sections = document.querySelectorAll('.settings-section');
    const hash = window.location.hash.substring(1);

    // Initialize by showing the correct tab/section
    if (hash && Array.from(tabs).some(tab => tab.dataset.tab === hash)) {
        switchTab(hash);
        
        // If attendance tab is loaded initially, initialize its functionality
        if (hash === 'attendance') {
            // Use a small delay to ensure DOM is ready
            setTimeout(() => {
                initializeAttendanceTab();
            }, 100);
        } else if (hash === 'campaigns') {
            setTimeout(() => {
                initializeCampaignsTab();
            }, 100);
        }
    }

    // Add click event listeners to tabs
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            switchTab(tab.dataset.tab);
        });
    });
    
    // If no hash or attendance is the default tab, initialize attendance functionality
    if (!hash || hash === 'attendance') {
        setTimeout(() => {
            initializeAttendanceTab();
        }, 100);
    } else if (hash === 'campaigns') {
        setTimeout(() => {
            initializeCampaignsTab();
        }, 100);
    }
});

// Campaign management functionality
function initializeCampaignsTab() {
    console.log('Initializing campaigns tab functionality...');
    
    // Make functions available globally for onclick handlers
    if (typeof window.openAddCampaignModal === 'undefined') {
        window.openAddCampaignModal = function() {
            const campaignModal = document.getElementById('campaign-modal');
            const modalTitle = document.getElementById('campaign-modal-title');
            const campaignForm = document.getElementById('campaign-form');
            const campaignIdField = document.getElementById('campaign-id');
            
            if (campaignModal && modalTitle && campaignForm) {
                modalTitle.textContent = 'Add Campaign';
                campaignForm.action = '/admin/manage_campaigns';
                campaignIdField.value = '';
                campaignForm.reset();
                
                // Set default values to prevent validation errors
                document.getElementById('vertical').value = 'ACA Health';
                document.getElementById('status').value = 'active';
                document.getElementById('commission_inr').value = '0';
                document.getElementById('payout_usd').value = '0';
                
                // Remove any existing method spoofing
                const existingMethod = campaignForm.querySelector('input[name="_method"]');
                if (existingMethod) {
                    existingMethod.remove();
                }
                
                campaignModal.classList.remove('hidden');
                console.log('Add campaign modal opened');
            } else {
                console.error('Campaign modal elements not found. Found:', {
                    modal: !!campaignModal,
                    title: !!modalTitle, 
                    form: !!campaignForm,
                    idField: !!campaignIdField
                });
            }
        };
    }
    
    if (typeof window.editCampaign === 'undefined') {
        window.editCampaign = function(id) {
            console.log('Editing campaign:', id);
            
            fetch(`/admin/campaigns/${id}/edit`)
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 404) {
                            throw new Error('Campaign not found. It may have been deleted.');
                        } else {
                            throw new Error(`Server error: ${response.status} ${response.statusText}`);
                        }
                    }
                    return response.json();
                })
                .then(campaign => {
                    const campaignModal = document.getElementById('campaign-modal');
                    const modalTitle = document.getElementById('campaign-modal-title');
                    const campaignForm = document.getElementById('campaign-form');
                    const campaignIdField = document.getElementById('campaign-id');
                    
                    if (campaignModal && modalTitle && campaignForm) {
                        modalTitle.textContent = 'Edit Campaign';
                        campaignIdField.value = campaign.id;
                        document.getElementById('campaign_name').value = campaign.campaign_name;
                        document.getElementById('vertical').value = campaign.vertical;
                        document.getElementById('did').value = campaign.did;
                        document.getElementById('status').value = campaign.status;
                        document.getElementById('commission_inr').value = campaign.commission_inr;
                        document.getElementById('payout_usd').value = campaign.payout_usd;
                        
                        campaignForm.action = `/admin/manage_campaigns/${id}`;
                        
                        // Remove existing method spoofing
                        const existingMethod = campaignForm.querySelector('input[name="_method"]');
                        if (existingMethod) {
                            existingMethod.remove();
                        }
                        
                        // Add PUT method
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PUT';
                        campaignForm.appendChild(methodInput);

                        campaignModal.classList.remove('hidden');
                        console.log('Edit campaign modal opened for campaign:', id);
                    } else {
                        console.error('Campaign modal elements not found for edit. Found:', {
                            modal: !!campaignModal,
                            title: !!modalTitle, 
                            form: !!campaignForm,
                            idField: !!campaignIdField
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading campaign data:', error);
                    alert('Error loading campaign data: ' + error.message + '\n\nThe campaign list will be refreshed.');
                    // Refresh the campaigns tab to show current state
                    switchTab('campaigns');
                });
        };
    }
    
    if (typeof window.deleteCampaign === 'undefined') {
        window.deleteCampaign = function(id) {
            console.log('Deleting campaign:', id);
            
            const deleteModal = document.getElementById('delete-campaign-modal');
            if (deleteModal) {
                deleteModal.classList.remove('hidden');
                
                const confirmBtn = document.getElementById('confirm-delete-btn');
                if (confirmBtn) {
                    confirmBtn.onclick = function() {
                        fetch(`/admin/manage_campaigns/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '<?php echo e(csrf_token()); ?>',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (response.ok) {
                                deleteModal.classList.add('hidden');
                                // Force reload the campaigns tab to show changes immediately
                                forceReloadTab('campaigns');
                                // Show success message
                                showSuccessMessage('Campaign deleted successfully!');
                                console.log('Campaign deleted successfully');
                            } else if (response.status === 404) {
                                deleteModal.classList.add('hidden');
                                alert('Campaign not found. It may have already been deleted.');
                                // Refresh the campaigns tab to show current state
                                forceReloadTab('campaigns');
                            } else {
                                throw new Error(`Delete failed: ${response.status} ${response.statusText}`);
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting campaign:', error);
                            deleteModal.classList.add('hidden');
                            alert('Error deleting campaign: ' + error.message);
                            // Refresh the campaigns tab to show current state
                            forceReloadTab('campaigns');
                        });
                    };
                } else {
                    console.error('Confirm delete button not found');
                }
            } else {
                console.error('Delete modal not found. Looking for: delete-campaign-modal');
            }
        };
    }
    
    // Set up modal close handlers
    const campaignModal = document.getElementById('campaign-modal');
    const deleteModal = document.getElementById('delete-campaign-modal');
    
    // Add global close functions for onclick handlers
    window.closeCampaignModal = function() {
        if (campaignModal) {
            campaignModal.classList.add('hidden');
        }
    };
    
    window.closeDeleteModal = function() {
        if (deleteModal) {
            deleteModal.classList.add('hidden');
        }
    };
    
    if (campaignModal) {
        const closeButtons = campaignModal.querySelectorAll('[onclick*="close"], .modal-close');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                campaignModal.classList.add('hidden');
            });
        });
    }
    
    if (deleteModal) {
        const closeButtons = deleteModal.querySelectorAll('[onclick*="close"], .modal-close');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });
        });
    }
    
    // Set up form submission
    const campaignForm = document.getElementById('campaign-form');
    if (campaignForm) {
        campaignForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Campaign form submitted');
            
            const formData = new FormData(this);
            const url = this.action;
            const method = formData.get('_method') || 'POST';

            // Log form data for debugging
            console.log('Form data being sent:');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            // For PUT requests, Laravel doesn't handle FormData properly with method spoofing
            // Convert to URLSearchParams for better compatibility
            let requestBody;
            let contentType = {};
            
            if (method === 'PUT') {
                // Convert FormData to URLSearchParams for PUT requests
                const params = new URLSearchParams();
                for (let [key, value] of formData.entries()) {
                    params.append(key, value);
                }
                requestBody = params;
                contentType['Content-Type'] = 'application/x-www-form-urlencoded';
            } else {
                // Use FormData for POST requests
                requestBody = formData;
            }

            fetch(url, {
                method: method === 'PUT' ? 'POST' : method, // Laravel expects POST with _method=PUT
                body: requestBody,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json',
                    ...contentType
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    campaignModal.classList.add('hidden');
                    // Force reload the campaigns tab to show changes immediately
                    forceReloadTab('campaigns');
                    // Show success message
                    const isEdit = formData.get('id') && formData.get('id').trim() !== '';
                    showSuccessMessage(isEdit ? 'Campaign updated successfully!' : 'Campaign created successfully!');
                    console.log('Campaign saved successfully');
                } else {
                    console.error('Campaign save failed:', data);
                    
                    // Show validation errors if they exist
                    if (data.errors) {
                        let errorMessage = 'Validation errors:\n';
                        Object.keys(data.errors).forEach(field => {
                            errorMessage += `${field}: ${data.errors[field].join(', ')}\n`;
                        });
                        alert(errorMessage);
                    } else {
                        alert(data.message || 'An error occurred while saving the campaign.');
                    }
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Network error occurred while saving the campaign.');
            });
        });
    }
    
    console.log('Campaigns tab initialization complete');
}

// Location functionality
function initializeAttendanceTab() {
    console.log('Initializing attendance tab functionality...');
    
    // Set up location type event listener (for the modal)
    const locationTypeSelect = document.getElementById('location_type');
    if (locationTypeSelect) {
        locationTypeSelect.addEventListener('change', function() {
            updateLocationPlaceholder(this.value);
        });
        
        // Set initial placeholder
        updateLocationPlaceholder(locationTypeSelect.value);
    }
    
    // Set up GPS capture button event listener (for the modal)
    const captureBtn = document.getElementById('capture-btn');
    if (captureBtn) {
        // Remove any existing event listeners to prevent duplicates
        captureBtn.replaceWith(captureBtn.cloneNode(true));
        const newCaptureBtn = document.getElementById('capture-btn');
        
        newCaptureBtn.addEventListener('click', function(e) {
            e.preventDefault();
            captureLocation();
        });
        
        console.log('GPS capture button event listener attached');
    }
    
    // Set up test geolocation button if it exists (for the modal)
    const testBtn = document.getElementById('test-geolocation-btn');
    if (testBtn) {
        testBtn.replaceWith(testBtn.cloneNode(true));
        const newTestBtn = document.getElementById('test-geolocation-btn');
        
        newTestBtn.addEventListener('click', function(e) {
            e.preventDefault();
            testGeolocation();
        });
        
        console.log('Test geolocation button event listener attached');
    }
    
    // Set up the attendance partial's GPS button
    const getCurrentLocationBtn = document.getElementById('getCurrentLocation');
    if (getCurrentLocationBtn) {
        console.log('Found getCurrentLocation button, setting up event listener...');
        
        // Remove any existing event listeners to prevent duplicates
        getCurrentLocationBtn.replaceWith(getCurrentLocationBtn.cloneNode(true));
        const newGetCurrentLocationBtn = document.getElementById('getCurrentLocation');
        
        newGetCurrentLocationBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('getCurrentLocation button clicked');
            
            // Call the main GPS capture function
            captureLocationForAttendance();
        });
        
        console.log('Attendance GPS button event listener attached');
    } else {
        console.log('getCurrentLocation button not found');
    }
    
    console.log('Attendance tab initialization complete');
}

// Fallback GPS function for attendance tab
function captureLocationForAttendance() {
    console.log('captureLocationForAttendance called');
    
    const getCurrentLocationBtn = document.getElementById('getCurrentLocation');
    const locationStatus = document.getElementById('locationStatus');
    
    console.log('getCurrentLocationBtn found:', !!getCurrentLocationBtn);
    console.log('locationStatus found:', !!locationStatus);
    
    if (!getCurrentLocationBtn || !locationStatus) {
        console.error('Required attendance elements not found');
        alert('Error: Required elements not found. Please refresh the page.');
        return;
    }
    
    const originalHTML = getCurrentLocationBtn.innerHTML;
    
    // Show loading state
    getCurrentLocationBtn.disabled = true;
    getCurrentLocationBtn.innerHTML = `
        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Getting Location...
    `;
    
    if (!navigator.geolocation) {
        console.error('Geolocation not supported');
        locationStatus.textContent = 'Geolocation is not supported by your browser.';
        locationStatus.className = 'text-xs mt-1 text-red-600 min-h-[16px]';
        getCurrentLocationBtn.disabled = false;
        getCurrentLocationBtn.innerHTML = originalHTML;
        return;
    }
    
    console.log('Starting geolocation request...');
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            console.log('GPS position received:', position);
            
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            const accuracy = position.coords.accuracy;
            
            console.log('Coordinates:', latitude, longitude, 'Accuracy:', accuracy);
            
            // Update location fields if they exist
            const latInput = document.getElementById('latitude');
            const lonInput = document.getElementById('longitude');
            const radiusInput = document.getElementById('radius_meters');
            const addressInput = document.getElementById('address');
            const locationValueInput = document.getElementById('location_value');
            
            console.log('Form inputs found:', {
                lat: !!latInput,
                lon: !!lonInput, 
                radius: !!radiusInput,
                address: !!addressInput,
                locationValue: !!locationValueInput
            });
            
            if (latInput) latInput.value = latitude.toFixed(6);
            if (lonInput) lonInput.value = longitude.toFixed(6);
            if (radiusInput && !radiusInput.value) radiusInput.value = Math.max(50, Math.round(accuracy));
            
            // Set the location_value field that the controller expects (latitude,longitude format)
            if (locationValueInput) {
                locationValueInput.value = `${latitude.toFixed(6)},${longitude.toFixed(6)}`;
                console.log('location_value set to:', locationValueInput.value);
            }
            
            // Show success status
            locationStatus.textContent = `Location captured (±${Math.round(accuracy)}m)`;
            locationStatus.className = 'text-xs mt-1 text-green-600 min-h-[16px]';
            
            // Try to get address via reverse geocoding (with fallback for CORS issues)
            if (addressInput) {
                // Try the main API first
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`, {
                    method: 'GET',
                    headers: {
                        'User-Agent': 'CRM-Location-App/1.0'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Geocoding service unavailable');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.display_name) {
                            addressInput.value = data.display_name;
                        } else {
                            addressInput.value = `GPS Location: ${latitude.toFixed(6)}, ${longitude.toFixed(6)}`;
                        }
                    })
                    .catch(error => {
                        console.warn('Primary geocoding failed, trying alternative method:', error);
                        
                        // Alternative: Try a different service or just use coordinates
                        const fallbackAddress = `GPS Location: ${latitude.toFixed(6)}, ${longitude.toFixed(6)}`;
                        addressInput.value = fallbackAddress;
                        console.log('Using fallback address:', fallbackAddress);
                    });
            }
            
            // Enable add button if it exists
            const addLocationBtn = document.getElementById('addLocationBtn');
            if (addLocationBtn) {
                addLocationBtn.disabled = false;
                addLocationBtn.classList.remove('bg-gray-400');
                addLocationBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            }
            
            // Show location preview if it exists
            const locationPreview = document.getElementById('locationPreview');
            const previewAddress = document.getElementById('previewAddress');
            const previewCoords = document.getElementById('previewCoords');
            
            if (locationPreview && previewAddress && previewCoords) {
                previewAddress.textContent = addressInput ? addressInput.value : `GPS Location: ${latitude.toFixed(6)}, ${longitude.toFixed(6)}`;
                previewCoords.textContent = `${latitude.toFixed(6)}, ${longitude.toFixed(6)} (±${Math.round(accuracy)}m accuracy)`;
                locationPreview.classList.remove('hidden');
            }
            
            // Reset button
            getCurrentLocationBtn.disabled = false;
            getCurrentLocationBtn.innerHTML = originalHTML;
        },
        function(error) {
            let errorMessage = 'Unable to retrieve location. ';
            
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage += 'Please allow location access.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage += 'Location information unavailable.';
                    break;
                case error.TIMEOUT:
                    errorMessage += 'Request timed out.';
                    break;
                default:
                    errorMessage += 'Unknown error occurred.';
                    break;
            }
            
            locationStatus.textContent = errorMessage;
            locationStatus.className = 'text-xs mt-1 text-red-600 min-h-[16px]';
            
            // Reset button
            getCurrentLocationBtn.disabled = false;
            getCurrentLocationBtn.innerHTML = originalHTML;
        },
        {
            enableHighAccuracy: true,
            timeout: 15000, // Increased timeout to 15 seconds
            maximumAge: 600000 // 10 minutes
        }
    );
}

function updateLocationPlaceholder(type) {
    const input = document.getElementById('location_value');
    const captureBtn = document.getElementById('capture-btn');
    
    switch(type) {
        case 'address':
            input.placeholder = 'e.g., 123 Main St, City, State';
            captureBtn.style.display = 'none';
            break;
        case 'ip':
            input.placeholder = 'e.g., 192.168.1.1';
            captureBtn.style.display = 'none';
            break;
        case 'geo':
            input.placeholder = 'e.g., 40.7128, -74.0060';
            captureBtn.style.display = 'block';
            break;
    }
}

function captureLocation() {
    console.log('captureLocation function called');
    
    const captureBtn = document.getElementById('capture-btn');
    const locationInput = document.getElementById('location_value');
    
    console.log('Button found:', captureBtn);
    console.log('Input found:', locationInput);
    
    if (!captureBtn || !locationInput) {
        console.error('Required elements not found');
        alert('Error: Required elements not found. Please refresh the page.');
        return;
    }
    
    const originalText = captureBtn.innerHTML;
    
    // Show loading state
    captureBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Getting...';
    captureBtn.disabled = true;
    
    console.log('Checking geolocation support...');
    
    if (!navigator.geolocation) {
        console.error('Geolocation not supported');
        alert('Geolocation is not supported by your browser.');
        captureBtn.innerHTML = originalText;
        captureBtn.disabled = false;
        return;
    }
    
    console.log('Geolocation supported, requesting position...');
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            console.log('Position received:', position);
            const coords = position.coords.latitude + ',' + position.coords.longitude;
            console.log('Formatted coords:', coords);
            
            locationInput.value = coords;
            console.log('Input value set to:', locationInput.value);
            
            // Reset button
            captureBtn.innerHTML = originalText;
            captureBtn.disabled = false;
            
            alert('Location captured successfully: ' + coords);
        }, 
        function(error) {
            console.error('Geolocation error:', error);
            let errorMessage = 'Unable to retrieve your location. ';
            
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage += 'Please allow location access in your browser.';
                    console.log('Permission denied');
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage += 'Location information is unavailable.';
                    console.log('Position unavailable');
                    break;
                case error.TIMEOUT:
                    errorMessage += 'The request to get user location timed out.';
                    console.log('Timeout');
                    break;
                default:
                    errorMessage += 'An unknown error occurred.';
                    console.log('Unknown error');
                    break;
            }
            
            alert(errorMessage);
            
            // Reset button
            captureBtn.innerHTML = originalText;
            captureBtn.disabled = false;
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 600000
        }
    );
}

function testGeolocation() {
    console.log('Testing geolocation...');
    console.log('Current URL protocol:', window.location.protocol);
    console.log('Current URL hostname:', window.location.hostname);
    console.log('Geolocation available:', !!navigator.geolocation);
    
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by this browser.');
        return;
    }
    
    const options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    };
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const coords = position.coords.latitude + ',' + position.coords.longitude;
            alert('Test successful! Your coordinates are: ' + coords);
            console.log('Test position:', position);
        },
        function(error) {
            console.error('Test error:', error);
            alert('Test failed: ' + error.message + ' (Code: ' + error.code + ')');
        },
        options
    );
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Set initial placeholder for location input
    updateLocationPlaceholder('address');
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ahshanali768/project-export/resources/views/admin/settings.blade.php ENDPATH**/ ?>