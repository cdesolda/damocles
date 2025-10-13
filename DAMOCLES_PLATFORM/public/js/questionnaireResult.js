var currentTab = 0;
showTab(currentTab);

function showTab(n) {
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";

    var scalesResult = document.getElementById("scalesResult");
    if (scalesResult) {
        if (n === 0) {
            scalesResult.style.display = "block";
        } else {
            scalesResult.style.display = "none";
        }
    }

    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }

    if (n == x.length - 1) {
        document.getElementById("nextBtn").style.display = "none";
    } else {
        document.getElementById("nextBtn").style.display = "inline";
        document.getElementById("nextBtn").innerHTML = "Next";
    }

    // Scroll to the top of the page
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });

    fixStepIndicator(n);
}

function nextPrev(n) {
    var x = document.getElementsByClassName("tab");
    if (n == 1 && !validateForm()) return false;

    x[currentTab].style.display = "none";
    currentTab = currentTab + n;

    showTab(currentTab);
}

function validateForm() {
    var x,
        valid = true;
    x = document.getElementsByClassName("tab");

    if (valid) {
        document.getElementsByClassName("step")[currentTab].className +=
            " finish";
    }

    return valid;
}

function fixStepIndicator(n) {
    var i,
        x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }
    x[n].className += " active";

    if (n === x.length - 1) {
        x[n].className += " finish";
    }
}
