// /**
//  * Video Seeking Fix for Windows Browsers
//  * Ensures video seeking works properly on Windows by ensuring metadata and seekable ranges are loaded
//  */
// document.addEventListener('DOMContentLoaded', function() {
//     const videos = document.querySelectorAll('.js-video');
    
//     videos.forEach(function(video) {
//         // Fix for Windows: Ensure video metadata is fully loaded before allowing seeks
//         video.addEventListener('loadedmetadata', function() {
//             // On Windows, we need to ensure seekable ranges are available
//             if (video.duration > 0 && video.seekable.length > 0) {
//                 // Small initialization seek to activate seekable ranges (Windows fix)
//                 const initTime = video.currentTime;
//                 if (initTime === 0) {
//                     video.currentTime = 0.01;
//                     setTimeout(function() {
//                         video.currentTime = 0;
//                     }, 50);
//                 }
//             }
//         });
        
//         // Ensure enough data is buffered for seeking (Windows browsers need this)
//         video.addEventListener('canplay', function() {
//             // Video is ready to play and seek
//             if (video.seekable.length === 0 && video.duration > 0) {
//                 // Force browser to load seekable ranges
//                 video.load();
//             }
//         });
        
//         // Handle seeking - ensure it works on Windows
//         video.addEventListener('seeking', function() {
//             // If video isn't ready, wait for it
//             if (video.readyState < 2) {
//                 const seekTime = video.currentTime;
//                 video.addEventListener('loadeddata', function() {
//                     video.currentTime = seekTime;
//                 }, { once: true });
//             }
//         });
//     });
// });

