// document.addEventListener('DOMContentLoaded', function() {
//     console.log('DOM loaded, initializing videos...');

//     // Add Bootstrap icons
//     if (!document.querySelector('link[href*="bootstrap-icons"]')) {
//         const link = document.createElement('link');
//         link.rel = 'stylesheet';
//         link.href = 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css';
//         document.head.appendChild(link);
//     }

//     const videos = document.querySelectorAll('.post-video');
//     console.log(`Found ${videos.length} videos`);

//     // Track active settings dropdown
//     let activeSettingsDropdown = null;

//     // Track which videos have been interacted with
//     const userInteractedVideos = new Set();

//     // Close all dropdowns
//     function closeAllDropdowns() {
//         console.log('Closing all dropdowns');
//         if (activeSettingsDropdown) {
//             activeSettingsDropdown.classList.remove('show');
//             activeSettingsDropdown = null;
//         }
//     }

//     // Close dropdown when clicking anywhere on document
//     document.addEventListener('click', function(e) {
//         console.log('Document click - checking dropdown');
//         // If dropdown is open
//         if (activeSettingsDropdown) {
//             console.log('Dropdown is open, checking click location');
//             // Check if click is on settings button OR inside dropdown
//             const clickedSettingsBtn = e.target.closest('.settings-btn');
//             const clickedInsideDropdown = e.target.closest('.settings-dropdown');

//             console.log('Clicked settings btn:', !!clickedSettingsBtn);
//             console.log('Clicked inside dropdown:', !!clickedInsideDropdown);

//             // If clicked outside both, close dropdown
//             if (!clickedSettingsBtn && !clickedInsideDropdown) {
//                 console.log('Click outside - closing dropdown');
//                 closeAllDropdowns();
//             }
//         }
//     });

//     // Close dropdown with ESC key
//     document.addEventListener('keydown', function(e) {
//         if (e.key === 'Escape' && activeSettingsDropdown) {
//             console.log('ESC pressed - closing dropdown');
//             closeAllDropdowns();
//         }
//     });

//     // Create controls for each video
//     videos.forEach(function(video, index) {
//         console.log(`Initializing video ${index + 1}`);
//         const wrapper = video.closest('.video-wrapper');

//         if (!wrapper) {
//             console.error('No video wrapper found for video', index);
//             return;
//         }

//         // Remove any existing controls
//         const existingControls = wrapper.querySelector('.video-controls');
//         if (existingControls) {
//             console.log('Removing existing controls');
//             existingControls.remove();
//         }

//         // Create controls HTML
//         const controlsHTML = `
//             <div class="video-loading" style="display: none;"></div>
//             <div class="video-controls">
//                 <!-- Left: Play button + Time -->
//                 <div class="controls-left">
//                     <button class="play-btn" type="button" title="Play/Pause">
//                         <i class="bi bi-play-fill"></i>
//                     </button>
//                     <div class="time-display">0:00 / 0:00</div>
//                 </div>

//                 <!-- Center: Timeline -->
//                 <div class="controls-center">
//                     <div class="timeline-container">
//                         <div class="timeline-progress"></div>
//                     </div>
//                 </div>

//                 <!-- Right: Settings + Expand + Mute -->
//                 <div class="controls-right">
//                     <button class="control-icon settings-btn" type="button" title="Settings">
//                         <i class="bi bi-gear"></i>
//                     </button>
//                     <div class="settings-dropdown">
//                         <div class="settings-item speed-toggle" data-action="speed">
//                             <span><i class="bi bi-speedometer2"></i>Playback speed</span>
//                             <span class="current-speed">1x</span>
//                         </div>
//                         <div class="speed-options" style="display: none;">
//                             <div class="settings-item speed-option" data-speed="0.5">0.5x</div>
//                             <div class="settings-item speed-option" data-speed="0.75">0.75x</div>
//                             <div class="settings-item speed-option active" data-speed="1">Normal</div>
//                             <div class="settings-item speed-option" data-speed="1.25">1.25x</div>
//                             <div class="settings-item speed-option" data-speed="1.5">1.5x</div>
//                             <div class="settings-item speed-option" data-speed="2">2x</div>
//                         </div>
//                         <div class="settings-item quality-toggle" data-action="quality">
//                             <span><i class="bi bi-hd"></i>Quality</span>
//                             <span class="current-quality">Auto</span>
//                         </div>
//                         <div class="quality-options" style="display: none;">
//                             <div class="settings-item quality-option active" data-quality="auto">Auto</div>
//                             <div class="settings-item quality-option" data-quality="720">720p HD</div>
//                             <div class="settings-item quality-option" data-quality="480">480p</div>
//                             <div class="settings-item quality-option" data-quality="360">360p</div>
//                         </div>
//                     </div>

//                     <button class="control-icon expand-btn" type="button" title="Fullscreen">
//                         <i class="bi bi-arrows-fullscreen"></i>
//                     </button>

//                     <button class="control-icon mute-btn muted" type="button" title="Mute/Unmute">
//                         <i class="bi bi-volume-mute-fill"></i>
//                     </button>
//                 </div>
//             </div>
//         `;

//         // Insert controls
//         wrapper.insertAdjacentHTML('beforeend', controlsHTML);
//         console.log('Controls added to video', index);

//         // Initialize this video
//         initVideoPlayer(video, wrapper, index);
//     });

//     // ===== AUTO-PLAY IN VIEWPORT (50% threshold) =====

//     const observer = new IntersectionObserver(function(entries) {
//         entries.forEach(function(entry) {
//             const video = entry.target;
//             const wrapper = video.closest('.video-wrapper');

//             if (entry.isIntersecting) {
//                 console.log('Video in viewport - auto-play check');
//                 // Auto-play muted videos that haven't been interacted with
//                 if (video.paused && video.muted && !userInteractedVideos.has(video)) {
//                     console.log('Auto-playing video');
//                     video.play().catch(function(e) {
//                         console.log('Auto-play prevented:', e);
//                     });
//                 }
//                 if (wrapper) wrapper.classList.add('show-controls');
//             } else {
//                 console.log('Video out of viewport');
//                 // Pause when out of viewport
//                 if (!video.paused) {
//                     video.pause();
//                 }
//                 if (wrapper) wrapper.classList.remove('show-controls');
//             }
//         });
//     }, {
//         threshold: 0.5,
//         rootMargin: '0px 0px -50px 0px'
//     });

//     // ===== INITIALIZE VIDEO PLAYER =====

//     function initVideoPlayer(video, wrapper, index) {
//         console.log(`Setting up video player ${index + 1}`);

//         // Get control elements
//         const playBtn = wrapper.querySelector('.play-btn');
//         const muteBtn = wrapper.querySelector('.mute-btn');
//         const expandBtn = wrapper.querySelector('.expand-btn');
//         const settingsBtn = wrapper.querySelector('.settings-btn');
//         const timelineProgress = wrapper.querySelector('.timeline-progress');
//         const timelineContainer = wrapper.querySelector('.timeline-container');
//         const timeDisplay = wrapper.querySelector('.time-display');
//         const loadingSpinner = wrapper.querySelector('.video-loading');
//         const settingsDropdown = wrapper.querySelector('.settings-dropdown');

//         // Verify all elements exist
//         console.log('Control elements found:', {
//             playBtn: !!playBtn,
//             muteBtn: !!muteBtn,
//             expandBtn: !!expandBtn,
//             settingsBtn: !!settingsBtn,
//             timelineContainer: !!timelineContainer,
//             settingsDropdown: !!settingsDropdown
//         });

//         if (!playBtn || !timelineContainer || !settingsBtn) {
//             console.error('Missing essential controls for video', index);
//             return;
//         }

//         // Start muted
//         video.muted = true;
//         muteBtn.classList.add('muted');
//         muteBtn.innerHTML = '<i class="bi bi-volume-mute-fill"></i>';

//         // Format time
//         function formatTime(seconds) {
//             if (!seconds || isNaN(seconds)) return '0:00';
//             const mins = Math.floor(seconds / 60);
//             const secs = Math.floor(seconds % 60);
//             return `${mins}:${secs.toString().padStart(2, '0')}`;
//         }

//         // Update time and timeline
//         function updateTimeDisplay() {
//             const current = formatTime(video.currentTime);
//             const duration = formatTime(video.duration);
//             timeDisplay.textContent = `${current} / ${duration}`;

//             if (video.duration && !isNaN(video.duration)) {
//                 const percent = (video.currentTime / video.duration) * 100;
//                 timelineProgress.style.width = `${percent}%`;
//             }
//         }

//         // Play/Pause
//         function togglePlayPause(e) {
//             console.log('Play/Pause clicked');
//             if (e) e.stopPropagation();

//             if (video.paused) {
//                 console.log('Playing video');
//                 video.play();
//                 userInteractedVideos.add(video);
//             } else {
//                 console.log('Pausing video');
//                 video.pause();
//             }
//         }

//         // Mute/Unmute
//         function toggleMute(e) {
//             console.log('Mute clicked');
//             if (e) e.stopPropagation();

//             video.muted = !video.muted;
//             console.log('Video muted:', video.muted);

//             muteBtn.classList.toggle('muted');

//             if (video.muted) {
//                 muteBtn.innerHTML = '<i class="bi bi-volume-mute-fill"></i>';
//             } else {
//                 muteBtn.innerHTML = '<i class="bi bi-volume-up-fill"></i>';
//             }

//             userInteractedVideos.add(video);
//         }

//         // Fullscreen
//         function toggleFullscreen(e) {
//             console.log('Fullscreen clicked');
//             if (e) e.stopPropagation();

//             if (!document.fullscreenElement) {
//                 wrapper.requestFullscreen().catch(console.error);
//                 wrapper.classList.add('fullscreen');
//             } else {
//                 document.exitFullscreen();
//                 wrapper.classList.remove('fullscreen');
//             }

//             userInteractedVideos.add(video);
//         }

//         // Settings dropdown
//         function toggleSettings(e) {
//             console.log('Settings button clicked');
//             if (e) e.stopPropagation();

//             // Check if this dropdown is already active
//             const isActive = settingsDropdown === activeSettingsDropdown;
//             console.log('Is dropdown active?', isActive);

//             // Close all dropdowns first
//             closeAllDropdowns();

//             // If this wasn't active, open it
//             if (!isActive) {
//                 console.log('Opening dropdown');
//                 settingsDropdown.classList.add('show');
//                 activeSettingsDropdown = settingsDropdown;
//             } else {
//                 console.log('Dropdown already active, keeping closed');
//             }
//         }

//         // Seek on timeline click
//         function seekOnTimeline(e) {
//             console.log('Timeline clicked');
//             e.stopPropagation();
//             e.preventDefault();

//             const rect = timelineContainer.getBoundingClientRect();
//             let clickX = e.clientX - rect.left;

//             // Ensure click is within bounds
//             if (clickX < 0) clickX = 0;
//             if (clickX > rect.width) clickX = rect.width;

//             const percent = clickX / rect.width;
//             console.log('Click percent:', percent);

//             if (video.duration && !isNaN(video.duration)) {
//                 const newTime = percent * video.duration;
//                 console.log('Seeking to:', newTime, 'seconds');

//                 // Set the new time
//                 video.currentTime = newTime;

//                 userInteractedVideos.add(video);
//             } else {
//                 console.log('Cannot seek: video duration not loaded');
//             }
//         }

//         // Set playback speed
//         function setPlaybackSpeed(speed) {
//             console.log('Setting playback speed:', speed);
//             video.playbackRate = parseFloat(speed);
//             wrapper.querySelector('.current-speed').textContent = speed === '1' ? 'Normal' : `${speed}x`;

//             wrapper.querySelectorAll('.speed-option').forEach(option => {
//                 option.classList.toggle('active', option.dataset.speed === speed);
//             });
//         }

//         // Set quality
//         function setQuality(quality) {
//             console.log('Setting quality:', quality);
//             wrapper.querySelector('.current-quality').textContent =
//                 quality === 'auto' ? 'Auto' : `${quality}p`;

//             wrapper.querySelectorAll('.quality-option').forEach(option => {
//                 option.classList.toggle('active', option.dataset.quality === quality);
//             });
//         }

//         // Toggle speed options
//         function toggleSpeedOptions(e) {
//             console.log('Speed toggle clicked');
//             e.stopPropagation();
//             const speedOptions = wrapper.querySelector('.speed-options');
//             const qualityOptions = wrapper.querySelector('.quality-options');

//             speedOptions.style.display = speedOptions.style.display === 'block' ? 'none' : 'block';
//             qualityOptions.style.display = 'none';
//         }

//         // Toggle quality options
//         function toggleQualityOptions(e) {
//             console.log('Quality toggle clicked');
//             e.stopPropagation();
//             const qualityOptions = wrapper.querySelector('.quality-options');
//             const speedOptions = wrapper.querySelector('.speed-options');

//             qualityOptions.style.display = qualityOptions.style.display === 'block' ? 'none' : 'block';
//             speedOptions.style.display = 'none';
//         }

//         // ===== EVENT LISTENERS =====

//         // Play button
//         playBtn.addEventListener('click', togglePlayPause);
//         console.log('Play button listener added');

//         // Video click
//         video.addEventListener('click', togglePlayPause);
//         console.log('Video click listener added');

//         // Mute button
//         muteBtn.addEventListener('click', toggleMute);
//         console.log('Mute button listener added');

//         // Fullscreen button
//         expandBtn.addEventListener('click', toggleFullscreen);
//         console.log('Fullscreen button listener added');

//         // Settings button
//         settingsBtn.addEventListener('click', toggleSettings);
//         console.log('Settings button listener added');

//         // Timeline click
//         timelineContainer.addEventListener('click', seekOnTimeline);
//         console.log('Timeline click listener added');

//         // Timeline drag
//         let isDragging = false;
//         timelineContainer.addEventListener('mousedown', function(e) {
//             console.log('Timeline drag started');
//             isDragging = true;
//             seekOnTimeline(e);

//             function handleMouseMove(e) {
//                 if (isDragging) {
//                     seekOnTimeline(e);
//                 }
//             }

//             function handleMouseUp() {
//                 console.log('Timeline drag ended');
//                 isDragging = false;
//                 document.removeEventListener('mousemove', handleMouseMove);
//                 document.removeEventListener('mouseup', handleMouseUp);
//             }

//             document.addEventListener('mousemove', handleMouseMove);
//             document.addEventListener('mouseup', handleMouseUp);
//         });

//         // Speed options
//         wrapper.querySelectorAll('.speed-option').forEach(option => {
//             option.addEventListener('click', function(e) {
//                 e.stopPropagation();
//                 setPlaybackSpeed(this.dataset.speed);
//             });
//         });

//         // Quality options
//         wrapper.querySelectorAll('.quality-option').forEach(option => {
//             option.addEventListener('click', function(e) {
//                 e.stopPropagation();
//                 setQuality(this.dataset.quality);
//             });
//         });

//         // Toggle speed/quality
//         const speedToggle = wrapper.querySelector('.speed-toggle');
//         const qualityToggle = wrapper.querySelector('.quality-toggle');

//         if (speedToggle) {
//             speedToggle.addEventListener('click', toggleSpeedOptions);
//             console.log('Speed toggle listener added');
//         }

//         if (qualityToggle) {
//             qualityToggle.addEventListener('click', toggleQualityOptions);
//             console.log('Quality toggle listener added');
//         }

//         // ===== VIDEO EVENTS =====

//         video.addEventListener('play', function() {
//             console.log('Video playing');
//             playBtn.innerHTML = '<i class="bi bi-pause-fill"></i>';
//             wrapper.classList.add('show-controls');
//             if (loadingSpinner) loadingSpinner.style.display = 'none';
//         });

//         video.addEventListener('pause', function() {
//             console.log('Video paused');
//             playBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
//             wrapper.classList.add('show-controls');
//         });

//         video.addEventListener('timeupdate', updateTimeDisplay);

//         video.addEventListener('loadedmetadata', function() {
//             console.log('Video metadata loaded');
//             updateTimeDisplay();
//             video.muted = true;
//             muteBtn.classList.add('muted');
//             muteBtn.innerHTML = '<i class="bi bi-volume-mute-fill"></i>';
//         });

//         video.addEventListener('waiting', function() {
//             console.log('Video waiting/buffering');
//             if (loadingSpinner) loadingSpinner.style.display = 'block';
//         });

//         video.addEventListener('canplay', function() {
//             console.log('Video can play');
//             if (loadingSpinner) loadingSpinner.style.display = 'none';
//         });

//         // Track interactions
//         video.addEventListener('volumechange', function() {
//             userInteractedVideos.add(video);
//         });

//         video.addEventListener('seeked', function() {
//             userInteractedVideos.add(video);
//         });

//         // Fullscreen change
//         document.addEventListener('fullscreenchange', function() {
//             if (!document.fullscreenElement) {
//                 wrapper.classList.remove('fullscreen');
//             }
//         });

//         // Show controls on hover
//         wrapper.addEventListener('mouseenter', function() {
//             wrapper.classList.add('show-controls');
//         });

//         wrapper.addEventListener('mouseleave', function() {
//             if (!video.paused) {
//                 setTimeout(() => {
//                     wrapper.classList.remove('show-controls');
//                 }, 1000);
//             }
//         });

//         // Observe for auto-play
//         observer.observe(video);
//         console.log('Video observer added');

//         // Initial update
//         updateTimeDisplay();
//         console.log('Video player initialization complete');
//     }

//     // ===== LIGHTBOX =====

//     function initLightbox() {
//         const images = document.querySelectorAll('.post-clickable-img');
//         const lightbox = document.getElementById('lightbox');
//         const lightboxImg = document.querySelector('.lightbox-img');
//         const lightboxClose = document.querySelector('.lightbox-close');

//         if (images.length && lightbox && lightboxImg && lightboxClose) {
//             console.log('Initializing lightbox with', images.length, 'images');

//             images.forEach(function(img) {
//                 img.addEventListener('click', function() {
//                     lightboxImg.src = this.src;
//                     lightbox.classList.add('active');
//                     document.body.style.overflow = 'hidden';
//                 });
//             });

//             function closeLightbox() {
//                 lightbox.classList.remove('active');
//                 document.body.style.overflow = 'auto';
//             }

//             lightboxClose.addEventListener('click', closeLightbox);

//             lightbox.addEventListener('click', function(e) {
//                 if (e.target === lightbox) {
//                     closeLightbox();
//                 }
//             });

//             document.addEventListener('keydown', function(e) {
//                 if (e.key === 'Escape' && lightbox.classList.contains('active')) {
//                     closeLightbox();
//                 }
//             });
//         } else {
//             console.log('Lightbox elements not found');
//         }
//     }

//     initLightbox();

//     console.log('All videos initialized');
// });
