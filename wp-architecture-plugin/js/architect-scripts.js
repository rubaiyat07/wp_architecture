jQuery(document).ready(function($) {
    // Initialize Bootstrap components
    if (typeof bootstrap !== 'undefined') {
        // Handle hire architect button clicks
        $(document).on('click', '.hire-architect-btn', function(e) {
            e.preventDefault();
            
            var architectId = $(this).data('architect-id');
            var architectName = $(this).data('architect-name');
            
            // Set modal content
            $('#hireArchitectModal .modal-title span').text(architectName);
            $('#hireArchitectModal #architect-id').val(architectId);
            
            // Show modal
            var hireModal = new bootstrap.Modal(document.getElementById('hireArchitectModal'));
            hireModal.show();
        });
        
        // Handle form submission via AJAX
        $(document).on('click', '#submit-hire-request', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var $form = $('#hire-architect-form');
            var formData = $form.serialize();
            
            $button.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
            );
            
            $.ajax({
                url: wpArchitecture.ajaxurl,
                type: 'POST',
                data: {
                    action: 'submit_architect_hire_request',
                    nonce: wpArchitecture.nonce,
                    form_data: formData
                },
                success: function(response) {
                    if (response.success) {
                        $form.replaceWith(
                            '<div class="alert alert-success">' + response.data.message + '</div>'
                        );
                        $button.hide();
                    } else {
                        alert('Error: ' + response.data.message);
                        $button.prop('disabled', false).text('Submit Request');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    $button.prop('disabled', false).text('Submit Request');
                }
            });
        });
        
        // Add architect form handling
        $(document).on('submit', '#add-architect-form', function(e) {
            e.preventDefault();
            var $form = $(this);
            var $submitBtn = $form.find('[type="submit"]');
            
            $submitBtn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...'
            );
            
            // Handle file upload
            var formData = new FormData(this);
            
            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    window.location.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                    $submitBtn.prop('disabled', false).text('Save Architect');
                }
            });
        });
        
        // Architect filtering
        $('#architect-search-form').on('submit', function(e) {
            e.preventDefault();
            
            var specialization = $('#specialization').val().toLowerCase();
            var experience = parseInt($('#experience').val()) || 0;
            var location = $('#location').val().toLowerCase();
            
            $('.architect-card').each(function() {
                var $card = $(this);
                var cardSpecialization = $card.data('specializations') || '';
                var cardExperience = parseInt($card.data('experience')) || 0;
                var cardLocation = $card.data('location') || '';
                
                var showCard = true;
                
                if (specialization && cardSpecialization.toLowerCase().indexOf(specialization) === -1) {
                    showCard = false;
                }
                
                if (experience && cardExperience < experience) {
                    showCard = false;
                }
                
                if (location && cardLocation.toLowerCase().indexOf(location) === -1) {
                    showCard = false;
                }
                
                $card.toggle(showCard);
            });
        });
    } else {
        console.warn('Bootstrap JS not loaded - some features may not work');
    }
    
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});