// document.addEventListener('DOMContentLoaded', function () {
//     const players = Array.from(document.querySelectorAll('.video-player'));
//     if (!players.length) return;

//     function formatTime(sec) {
//         sec = sec || 0;
//         const s = Math.floor(sec % 60).toString().padStart(2, '0');
//         const m = Math.floor(sec / 60);
//         return `${m}:${s}`;
//     }

//     players.forEach(player => {
//         const video       = player.querySelector('.video-player__video');
//         const bigPlay     = player.querySelector('.video-player__big-play');
//         const btnPlay     = player.querySelector('.vp-play-pause');
//         const btnMute     = player.querySelector('.vp-mute');
//         const btnFull     = player.querySelector('.vp-fullscreen');
//         const progBar     = player.querySelector('.vp-progress-bar');
//         const progFill    = player.querySelector('.vp-progress-fill');
//         const spanCurrent = player.querySelector('.vp-current');
//         const spanDuration= player.querySelector('.vp-duration');
//         const settingsToggle = player.querySelector('.vp-settings-toggle');
//         const settingsMenu   = player.querySelector('.vp-settings-menu');
//         const qualityGroup   = player.querySelector('.vp-quality-group');
//         const qualityItemsContainer = player.querySelector('.vp-quality-items');

//         // basic setup
//         if (!video) return;
//         video.muted = true;
//         video.removeAttribute('controls');

//         // === DURATION ===
//         video.addEventListener('loadedmetadata', () => {
//             if (!isNaN(video.duration) && video.duration > 0 && spanDuration) {
//                 spanDuration.textContent = formatTime(video.duration);
//             }
//         });

//         // === TIME + PROGRESS ===
//         video.addEventListener('timeupdate', () => {
//             if (spanCurrent) {
//                 spanCurrent.textContent = formatTime(video.currentTime);
//             }
//             if (video.duration && !isNaN(video.duration) && progFill) {
//                 const percent = (video.currentTime / video.duration) * 100;
//                 progFill.style.width = `${percent}%`;
//             }
//         });

//         // === PLAY / PAUSE ===
//         function updatePlayState() {
//             if (!btnPlay) return;
//             if (video.paused) {
//                 btnPlay.textContent = '▶';
//                 player.classList.remove('playing');
//             } else {
//                 btnPlay.textContent = '⏸';
//                 player.classList.add('playing');
//             }
//         }

//         function togglePlay() {
//             if (video.paused) {
//                 video.play().catch(() => {});
//             } else {
//                 video.pause();
//             }
//         }

//         video.addEventListener('play', updatePlayState);
//         video.addEventListener('pause', updatePlayState);

//         if (bigPlay) {
//             bigPlay.addEventListener('click', e => {
//                 e.stopPropagation();
//                 togglePlay();
//             });
//         }

//         if (btnPlay) {
//             btnPlay.addEventListener('click', e => {
//                 e.stopPropagation();
//                 togglePlay();
//             });
//         }

//         // if you want click on video to play/pause, keep this:
//         // video.addEventListener('click', togglePlay);

//         // === MUTE ===
//         function updateMuteIcon() {
//             if (!btnMute) return;
//             btnMute.textContent = video.muted ? '🔇' : '🔈';
//         }
//         updateMuteIcon();

//         if (btnMute) {
//             btnMute.addEventListener('click', e => {
//                 e.stopPropagation();
//                 video.muted = !video.muted;
//                 updateMuteIcon();
//             });
//         }

//         // === PROGRESS CLICK SEEK (main fix) ===
//         if (progBar) {
//             progBar.addEventListener('click', function (e) {
//                 e.preventDefault();
//                 e.stopPropagation();

//                 const bar   = e.currentTarget;
//                 const rect  = bar.getBoundingClientRect();
//                 const x     = e.clientX - rect.left;
//                 const ratio = Math.min(Math.max(x / rect.width, 0), 1);

//                 if (!isNaN(video.duration) && video.duration > 0) {
//                     const wasPlaying = !video.paused;
//                     video.currentTime = ratio * video.duration;
//                     if (wasPlaying) {
//                         video.play().catch(() => {});
//                     }
//                 }
//             });
//         }

//         // === FULLSCREEN ===
//         if (btnFull) {
//             btnFull.addEventListener('click', e => {
//                 e.stopPropagation();
//                 if (!document.fullscreenElement) {
//                     player.requestFullscreen?.();
//                 } else {
//                     document.exitFullscreen?.();
//                 }
//             });
//         }

//         // === SETTINGS: SPEED ===
//         if (settingsToggle && settingsMenu) {
//             settingsToggle.addEventListener('click', e => {
//                 e.stopPropagation();
//                 settingsMenu.classList.toggle('open');
//             });

//             document.addEventListener('click', e => {
//                 if (!settingsMenu.contains(e.target) && e.target !== settingsToggle) {
//                     settingsMenu.classList.remove('open');
//                 }
//             });

//             settingsMenu.querySelectorAll('[data-speed]').forEach(btn => {
//                 btn.addEventListener('click', () => {
//                     const speed = parseFloat(btn.dataset.speed);
//                     video.playbackRate = speed;
//                     settingsMenu
//                         .querySelectorAll('[data-speed]')
//                         .forEach(b => b.removeAttribute('data-active'));
//                     btn.setAttribute('data-active', 'true');
//                 });
//             });
//         }

//         // === SETTINGS: QUALITY (still supports your data-sources) ===
//         if (qualityGroup && qualityItemsContainer) {
//             let sources = [];
//             try {
//                 sources = JSON.parse(player.dataset.sources || '[]');
//             } catch (_) {}

//             if (sources.length) {
//                 sources.forEach((s, idx) => {
//                     const qBtn = document.createElement('button');
//                     qBtn.type = 'button';
//                     qBtn.className = 'vp-settings-item';
//                     qBtn.textContent = s.label || `Q${idx + 1}`;
//                     qBtn.dataset.src = s.src;
//                     if (idx === 0) qBtn.setAttribute('data-active', 'true');
//                     qualityItemsContainer.appendChild(qBtn);

//                     qBtn.addEventListener('click', () => {
//                         const currentTime = video.currentTime;
//                         const wasPlaying  = !video.paused;

//                         qualityItemsContainer
//                             .querySelectorAll('.vp-settings-item')
//                             .forEach(b => b.removeAttribute('data-active'));
//                         qBtn.setAttribute('data-active', 'true');

//                         const newSrc = qBtn.dataset.src;
//                         let sourceEl = video.querySelector('source');
//                         if (!sourceEl) {
//                             sourceEl = document.createElement('source');
//                             video.appendChild(sourceEl);
//                         }
//                         sourceEl.src = newSrc;

//                         video.pause();
//                         video.load();
//                         video.addEventListener(
//                             'loadedmetadata',
//                             () => {
//                                 video.currentTime = Math.min(
//                                     currentTime,
//                                     video.duration || currentTime
//                                 );
//                                 if (wasPlaying) video.play().catch(() => {});
//                             },
//                             { once: true }
//                         );
//                     });
//                 });
//             } else {
//                 qualityGroup.style.display = 'none';
//             }
//         }
//     });
// });
