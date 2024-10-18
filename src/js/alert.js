function showAlert() {
    const element = document.getElementsByClassName('custom-alert')[0]; // Get the first element with the class
    const text = element.textContent.trim();  // Remove unnecessary whitespace and line breaks

    // Search for "error=" or "msg="
    const errorMatch = text.match(/error=(.+)/);
    const msgMatch = text.match(/msg=(.+)/);

    const messageContainer = document.createElement('div'); // Create a new div for the message
    messageContainer.style.position = 'fixed'; // Fix the position
    messageContainer.style.top = '0'; // Align to the top
    messageContainer.style.left = '0'; // Align to the left
    messageContainer.style.width = '100%'; // Full width
    messageContainer.style.zIndex = '9999'; // Make sure it appears above other content
    messageContainer.style.textAlign = 'center'; // Center the text
    document.body.appendChild(messageContainer); // Append to body

    if (errorMatch) {
        // If "error=" is found, style it accordingly
        const errorText = errorMatch[1].trim();  // Extract the part after "error="
        messageContainer.className = 'alert alert-danger'; // Set Bootstrap class for error
        messageContainer.textContent = errorText; // Set the text content to the error message
        messageContainer.style.display = 'block'; // Show the message container
    } else if (msgMatch) {
        // If "msg=" is found, style it accordingly
        const msgText = msgMatch[1].trim(); // Extract the part after "msg="
        messageContainer.className = 'alert alert-success'; // Set Bootstrap class for success message
        messageContainer.textContent = msgText; // Set the text content to the message
        messageContainer.style.display = 'block'; // Show the message container
    }

    // Create a close button
    const closeButton = document.createElement('button');
    closeButton.textContent = 'X'; // Button text
    closeButton.style.position = 'absolute'; // Position it
    closeButton.style.top = '10px'; // Position it at the top
    closeButton.style.right = '10px'; // Position it on the right
    closeButton.style.backgroundColor = 'transparent'; // Transparent background
    closeButton.style.border = 'none'; // No border
    closeButton.style.color = 'white'; // White text color
    closeButton.style.cursor = 'pointer'; // Pointer cursor
    messageContainer.appendChild(closeButton); // Add the button to the message container

    // Close the alert when the button is clicked
    closeButton.addEventListener('click', () => {
        messageContainer.remove();
    });

    // Auto-remove the alert after a certain duration (e.g., 5 seconds)
    setTimeout(() => {
        messageContainer.remove();
    }, 10000); // Adjust the time in milliseconds as needed
}

// Call function onload
window.onload = showAlert;
