  let currentColor = 'rgba(255, 0, 0, 0.5)';
  
  function createColorPalette() {
    const palette = document.getElementById('colorPalette');
    let isFirst = true;
    colors.forEach(color => {
      const swatch = document.createElement('div');
      swatch.className = 'color-swatch';
      swatch.hexColor = color;
      swatch.style.backgroundColor = color;
      if(isFirst){
        swatch.classList.add('selected');
        isFirst = false;
      }
      swatch.addEventListener('click', () => {
        const rgbaColor = hexToRgba(color, 0.5);
        currentColor = rgbaColor;
        
        // Remove the 'selected' class from all swatches
        document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('selected'));
        // Add the 'selected' class to the clicked swatch
        swatch.classList.add('selected');
      });
      palette.appendChild(swatch);
    });
  }
  
  function hexToRgba(hex, alpha) {
    const bigint = parseInt(hex.slice(1), 16);
    const r = (bigint >> 16) & 255;
    const g = (bigint >> 8) & 255;
    const b = bigint & 255;
    return `rgba(${r},${g},${b},${alpha})`;
  }
  
  async function setupCamera() {
    const video = document.getElementById('video');
    const cameraSetting = {
      audio: false,
      video: {
        facingMode: "user",
      }
    }
    return new Promise((resolve) => {
      navigator.mediaDevices.getUserMedia(cameraSetting)
        .then((mediaStream) => {
          video.srcObject = mediaStream;
          video.onloadedmetadata = () => {
            resolve(video);
          };
        })
        .catch((err) => {
          console.log(err.toString());
          resolve(null);
        });
    });
  }
  
  async function loadModels() {
    await faceapi.nets.tinyFaceDetector.loadFromUri('/js/models');
    // await faceapi.nets.faceLandmark68Net.loadFromUri('/js/models');
    await faceapi.nets.faceLandmark68TinyNet.loadFromUri('/js/models');
  }
  
  async function onPlay() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('overlay');
    const context = canvas.getContext('2d');
  
    const displaySize = { width: video.videoWidth, height: video.videoHeight };
    faceapi.matchDimensions(canvas, displaySize);
  
    async function detect() {
      const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks(true);
      const resizedDetections = faceapi.resizeResults(detections, displaySize);
      
      context.clearRect(0, 0, canvas.width, canvas.height);
  
      if (resizedDetections.length > 0) {
        const landmarks = resizedDetections[0].landmarks;
        const mouth = landmarks.getMouth();
  
        // Draw outer mouth
        context.fillStyle = currentColor;
        context.beginPath();
        context.moveTo(mouth[0].x, mouth[0].y);
        for (let i = 1; i < mouth.length; i++) {
          context.lineTo(mouth[i].x, mouth[i].y);
        }
        context.closePath();
        context.fill();
  
        // Remove inner mouth (excluding teeth area)
        context.globalCompositeOperation = 'destination-out';
        context.beginPath();
        context.moveTo(mouth[12].x, mouth[12].y);
        for (let i = 13; i <= 19; i++) {
          context.lineTo(mouth[i].x, mouth[i].y);
        }
        context.closePath();
        context.fill();
        context.globalCompositeOperation = 'source-over';
  
        // Draw landmarks for debugging
        // context.fillStyle = 'blue';
        // mouth.forEach((point, index) => {
        //   context.fillRect(point.x - 2, point.y - 2, 4, 4);
        //   context.fillText(index, point.x + 2, point.y + 2);
        // });
      }
  
      requestAnimationFrame(detect);
    }
  
    detect();
  }
  
  async function main() {
    createColorPalette();
    await loadModels();
    await setupCamera();
    onPlay();
  }
  
  main();
  