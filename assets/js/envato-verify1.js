$(document).ready(function() {
    console.log("Document is ready.");

    // Function to generate a simple hash for domain identification
    function simpleHash(str) {
        let hash = 0;
        if (str.length === 0) return hash;
        for (let i = 0; i < str.length; i++) {
            const char = str.charCodeAt(i);
            hash = ((hash << 5) - hash) + char; // Bitwise left shift
            hash |= 0; // Convert to 32bit integer
        }
        return hash;
    }

    // Function to generate SHA-256 hash
    async function sha256(str) {
        const buffer = await crypto.subtle.digest('SHA-256', new TextEncoder().encode(str));
        return Array.from(new Uint8Array(buffer)).map(b => b.toString(16).padStart(2, '0')).join('');
    }

    function getBasePath() {
        var script = document.querySelector('script[data-app-script="myApp"]');
        return script ? script.src.substring(0, script.src.lastIndexOf('/assets/js/')) : '';
    }

    function checkFileExists(callback) {
        var fileUrl = getBasePath() + '/assets/js/vdata.json';
        $.ajax({
            url: fileUrl,
            dataType: 'json',
            type: 'GET',
            success: function(data) {
                callback(true, data);  // Pass the hash back if the file exists
            },
            error: function() {
                callback(false, null);  // File does not exist
            }
        });
    }

    function showModal(valid = true) {
        var modalHTML = `
            <div class="modal fade" id="purchaseCodeModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel">Verify Envato Purchase Code</h5>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info mt-2" role="alert">
                                If you change the company name, you need to reset the license.
                                <a class="btn btn-sm btn-warning ml-2" href="https://api.zivaansolutions.com/reset" target="_blank" style="text-decoration:none;color:black">Reset License</a>
                            </div>
                            <form id="verifyForm">
                                <div class="form-group">
                                    <label for="purchaseCode">Enter Purchase Code:</label>
                                    <input type="text" class="form-control" id="purchaseCode" name="purchaseCode" required>                                    
                                </div>
                                <div class="form-group">
                                    <label for="companyName">Enter Company Name:</label>
                                    <input type="text" class="form-control" id="companyName" name="companyName" required>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary" id="verifyButton">Verify</button>
                                    </div>
                                </div>
                            </form>
                            ${!valid ? '<div class="alert alert-danger default-error">Purchase code is not valid or does not match.</div>' : ''}
                            <div id="result" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>`;
        $('body').append(modalHTML);
        $('#purchaseCodeModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    function checkDomainAndShowModal() {
        var excludedHashes = [-1372627182, -1052859433, 127764498, 994943894]; // Example hashes
        var currentDomainHash = simpleHash(window.location.hostname);
        var dbCompanyName = "{{companyName}}";

        if (!excludedHashes.includes(currentDomainHash)) {
            checkFileExists(async function(exists, existingData) {
                if (!exists) {
                    showModal();
                } else {
                    const companyName = existingData.company_name;
                    const purchaseCode = existingData.purchase_code;
                    const generatedHash = await sha256(dbCompanyName + purchaseCode);
                    const existingHash = existingData.hash;

                    if (existingHash != generatedHash || companyName != dbCompanyName) {
                        showModal(false);                        
                    }
                }
            });
        } else {
            console.log("Verification skipped on excluded domain.");
        }
    }

    $(document).on('submit', '#verifyForm', async function(e) {
        e.preventDefault();

        // Check if the user has an active internet connection
        if (!navigator.onLine) {
            $('#result').html('<div class="alert alert-warning" id="offline-message">You are offline. Please check your internet connection and try again.</div>');
            return;
        }

        // Hide default error message
        $('.default-error').remove();

        const companyName = $('#companyName').val();
        const purchaseCode = $('#purchaseCode').val();
        const hash = await sha256(companyName + purchaseCode);
        const verifyUrl = getBasePath() + '/licence/verify';

        $('#verifyButton').prop('disabled', true).text('Verifying...');

        $.ajax({
            url: verifyUrl,
            type: 'POST',
            data: { companyName, purchaseCode, hash }, // Send the hash to the server
            success: function(response) {
                const data = JSON.parse(response);
                const resultHtml = data.success ? 
                    `<div class="alert alert-success">${data.message}</div>` : 
                    `<div class="alert alert-danger">${data.message}</div>`;
                $('#result').html(resultHtml);
                if (data.success) {
                    $('#verifyButton').hide();
                    setTimeout(() => $('#purchaseCodeModal').modal('hide'), 3000);
                } else {
                    $('#verifyButton').prop('disabled', false).text('Verify');
                }
            },
            error: function() {
                $('#result').html('<div class="alert alert-danger">Error communicating with the server.</div>');
                $('#verifyButton').prop('disabled', false).text('Verify');
                console.log("AJAX request failed.");
            }
        });
    });

    // Listen for the 'online' event to clear the offline message content
    window.addEventListener('online', function() {
        $('#offline-message').html('');  // Clear the content of the offline message when back online
    });

    checkDomainAndShowModal(); // Initiate domain and file check when document is ready
});
