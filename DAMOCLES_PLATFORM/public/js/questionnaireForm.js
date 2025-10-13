var currentTab = 0;
showTab(currentTab);

function showTab(n) {
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";

    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }

    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").style.display = "none";
        if (document.getElementById("submit")) {
            document.getElementById("submit").style.display = "block";
        }
    } else {
        document.getElementById("nextBtn").style.display = "inline";
        if (document.getElementById("submit")) {
            document.getElementById("submit").style.display = "none";
        }
        document.getElementById("nextBtn").innerHTML = "Next";
    }

    //Scroll to the top of the page
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
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
    var x, y, i, valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTab].getElementsByTagName("input");

    for (i = 0; i < y.length; i++) {
        if (y[i].type === "radio") {
            var name = y[i].name;
            var radios = document.getElementsByName(name);
            var checked = false;
            for (var j = 0; j < radios.length; j++) {
                if (radios[j].checked) {
                    checked = true;
                    break;
                }
            }
            if (!checked) {
                valid = false;
                y[i].parentElement.className += " invalid";
            }
        }
    }

    if (!valid) {
        //Show the modal for incomplete questions
        const modalEvent = new CustomEvent('open-modal', {
            detail: 'error-modal'
        });
        window.dispatchEvent(modalEvent);
    }

    if (valid) {
        document.getElementsByClassName("step")[currentTab].className += " finish";
    }

    return valid;
}

function fixStepIndicator(n) {
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }
    x[n].className += " active";

    if (n === x.length - 1) {
        x[n].className += " finish";
    }
}