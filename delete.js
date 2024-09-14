let deleteButtons = document.querySelectorAll('.deleteBtn');

deleteButtons.forEach(button => {
    button.addEventListener('click', function(e){
        e.preventDefault();

        let member = this.dataset.name; // Corrected to `data-name`
        let memberId = this.dataset.id;

        let response = confirm("Do you want to delete the Member " + member + "?");

        if(response){
            fetch('Delete.php?id=' + memberId, {  // Corrected URL format
                method: 'GET'
            })
            .then(response => response.text())
            .then(data => {
                if(data === 'success'){
                    // Reload the page or redirect to the main product list
                    window.location.href = 'membership.php';
                } else {
                    alert('Failed to delete the program.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error occurred while deleting.');
            });
        }
    });
});
