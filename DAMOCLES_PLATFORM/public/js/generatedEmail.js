let selectedText = "";
let selectedTextarea = null;
let editCount = {};

function showTooltip(event, textarea) {
    let start = textarea.selectionStart;
    let end = textarea.selectionEnd;
    let selection = textarea.value.substring(start, end).trim();

    if (selection.length > 0) {
        selectedText = selection;
        selectedTextarea = textarea;

        let tooltip = document.getElementById("tooltip");
        tooltip.style.left = `${event.pageX}px`;
        tooltip.style.top = `${event.pageY}px`;
        tooltip.classList.remove("hidden");
    } else {
        hideTooltip();
    }
}

// Hide the tooltip
function hideTooltip() {
    document.getElementById("tooltip").classList.add("hidden");
}

function submitRewrite(index, llmId) {
    const subjectElement = document.getElementById(`subject-${index}`);
    const bodyElement = document.getElementById(`body-${index}`);
    const explanationElement = document.getElementById(`explanation-${index}`);
    const rewriteTextAreaElement = document.getElementById(`rewriteTextArea`);

    if (!selectedText || !subjectElement || !bodyElement || !explanationElement || !rewriteTextAreaElement) {
        console.error(`Element with index ${index} not found.`);
        return;
    }

    const rewriteTextArea = rewriteTextAreaElement.value;

    // Loading screen
    document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');

    const subject = subjectElement.value;
    const body = bodyElement.value;
    const explanation = explanationElement.innerHTML;

    // Data to send to the backend
    const data = {
        llmId: llmId,
        subject: subject,
        body: body,
        explanation: explanation,
        selected_text: selectedText,
        rewriteTextArea: rewriteTextArea
    };

    fetch('/phishing-emails/rewrite', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(responseData => {
            if (responseData.success) {
                hideTooltip();

                // Update subject, body, explanation, and modal
                document.getElementById(`subject-${index}`).value = responseData.updatedData.subject;
                document.getElementById(`body-${index}`).value = responseData.updatedData.body;

                const explanationMarkdown = Array.isArray(responseData.updatedData.explanation)
                    ? responseData.updatedData.explanation.join("\n")
                    : responseData.updatedData.explanation;

                document.getElementById(`explanation-${index}`).innerHTML = marked.parse(explanationMarkdown);

                rewriteTextAreaElement.value = '';
                // Increments the "Edited" value and change border color
                if (!editCount[`body-${index}`]) {
                    editCount[`body-${index}`] = 0;
                }
                editCount[`body-${index}`] += 1;

                document.getElementById(`body-${index}`).classList.remove("border-sky-700");
                document.getElementById(`body-${index}`).style.border = "1.5px solid green";

                document.getElementById(`explanation-${index}`).classList.remove("border-sky-700");
                document.getElementById(`explanation-${index}`).style.border = "1.5px solid green";
                updateEditedLabel(`body-${index}`, editCount[`body-${index}`]);

                window.dispatchEvent(new CustomEvent('close-modal', {
                    detail: 'rewrite-modal'
                }));
            }
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('flex', 'hidden');
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Function to update the label "Edited(n)"
function updateEditedLabel(elementId, count) {
    let label = document.getElementById(`${elementId}-edited`);
    const container = document.getElementById(elementId).parentElement;

    if (!label) {
        label = document.createElement('p');
        label.id = `${elementId}-edited`;
        label.classList.add('text-sm', 'font-bold');
        label.style.color = "green";
        container.insertBefore(label, document.getElementById(elementId));
    }

    label.textContent = `Edited(${count})`;
}

// Tab emails
var currentTab = 0;
showTab(currentTab);

function showTab(n) {
    var x = document.getElementsByClassName("email-container");
    if (x.length === 0) return; // Exit if no tabs are found

    x[n].style.display = "block";

    if (n === 0) {
        if (document.getElementById("prevBtn")) {
            document.getElementById("prevBtn").style.display = "none";
        }
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }

    if (n === (x.length - 1)) {
        if (document.getElementById("nextBtn")) {
            document.getElementById("nextBtn").style.display = "none";
        }
    } else {
        document.getElementById("nextBtn").style.display = "inline";
    }

    fixStepIndicator(n);
}

function nextPrev(n) {
    var x = document.getElementsByClassName("email-container");
    if (x.length === 0) return;

    x[currentTab].style.display = "none";
    currentTab = currentTab + n;

    if (currentTab >= x.length) {
        currentTab = x.length - 1;
    } else if (currentTab < 0) {
        currentTab = 0;
    }

    showTab(currentTab);
}

function fixStepIndicator(n) {
    var i, x = document.getElementsByClassName("step");
    if (x.length === 0) return;

    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }
    x[n].className += " active";
}