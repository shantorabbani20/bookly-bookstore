function initializeCountdown(startTime, endTime, elementId) {
  var countDownDate = new Date(endTime).getTime();

  // Update the count down every 1 second
  var x = setInterval(function () {
    // Get the current time
    var now = new Date().getTime();

    // Find the distance between now and the count down date
    var distance = countDownDate - now;

    // Time calculations for days, hours, minutes, and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // Update the target element with the countdown
    var element = document.getElementById(elementId);
    if (element) {
      element.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
    }

    // If the countdown is over, stop the interval and update the text
    if (distance < 0) {
      clearInterval(x);
      if (element) {
        element.innerHTML = "EXPIRED";
      }
    }
  }, 1000);
}
