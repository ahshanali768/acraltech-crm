@extends('layouts.admin')

@section('title', 'Attendance Details')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Attendance Details</h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">View detailed attendance information</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.attendance.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Attendance
                        </a>
                    </div>
                </div>
            </div>

            <!-- Attendance Details Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Attendance Record</h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Information -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">User Information</h4>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center mr-4">
                                        <span class="text-lg font-bold text-white">
                                            <?= strtoupper(substr($attendance->user->name ?? 'NA', 0, 2)) ?>
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($attendance->user->name ?? 'N/A') ?></div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($attendance->user->username ?? 'N/A') ?></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($attendance->user->email ?? 'N/A') ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance Information -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Attendance Details</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Date</label>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        <?= $attendance->date ? date('l, F j, Y', strtotime($attendance->date)) : 'N/A' ?>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Status</label>
                                    <div class="mt-1">
                                        <?php
                                        $statusColors = [
                                            'present' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'late' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                            'absent' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            'half_day' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                                        ];
                                        $statusColor = $statusColors[$attendance->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
                                        ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $statusColor ?>">
                                            <?= ucfirst($attendance->status) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Time Information -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Login Time</div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                <?= $attendance->login_time ? date('g:i A', strtotime($attendance->login_time)) : 'N/A' ?>
                            </div>
                            <?php if ($attendance->login_time): ?>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <?= date('M j, Y', strtotime($attendance->login_time)) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Logout Time</div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                <?= $attendance->logout_time ? date('g:i A', strtotime($attendance->logout_time)) : 'Still Active' ?>
                            </div>
                            <?php if ($attendance->logout_time): ?>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <?= date('M j, Y', strtotime($attendance->logout_time)) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Hours Worked</div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                <?= $attendance->hours_worked ?? 'N/A' ?>
                            </div>
                            <?php if ($attendance->login_time && $attendance->logout_time): ?>
                                <?php
                                $loginTime = new DateTime($attendance->login_time);
                                $logoutTime = new DateTime($attendance->logout_time);
                                $duration = $loginTime->diff($logoutTime);
                                ?>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <?= $duration->format('%h hours %i minutes') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <?php if ($attendance->location || $attendance->notes): ?>
                        <div class="mt-8">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Additional Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php if ($attendance->location): ?>
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400">Location</label>
                                        <div class="text-sm text-gray-900 dark:text-white mt-1">
                                            <?= htmlspecialchars($attendance->location) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($attendance->notes): ?>
                                    <div>
                                        <label class="text-xs text-gray-500 dark:text-gray-400">Notes</label>
                                        <div class="text-sm text-gray-900 dark:text-white mt-1">
                                            <?= htmlspecialchars($attendance->notes) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <button onclick="editAttendance()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Record
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editAttendance() {
    alert('Edit attendance feature will be implemented soon!');
}
</script>
@endsection
