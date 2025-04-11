setInterval(myTimer, 1000);
      function myTimer() {
        // console.log("Interval event");
        const pb = document.querySelector(".progress-bar > div");
        let progress = parseInt(pb.style.width, 10);
        // console.log("Widht Before: " + progress);
        if (progress < 100) {
          progress = progress + 20;
        } else {
          progress = 0;
        }
        // console.log("Widht After: " + progress);
        pb.style.width = progress + "%";
      }



