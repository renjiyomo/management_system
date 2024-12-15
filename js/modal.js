document.getElementById('openAddModal').addEventListener('click', () => {
    document.getElementById('addStudentModal').style.display = 'block';
});

document.getElementById('closeAddModal').addEventListener('click', () => {
    document.getElementById('addStudentModal').style.display = 'none';
});

document.getElementById('closeUpdateModal').addEventListener('click', () => {
    document.getElementById('updateStudentModal').style.display = 'none';
});

document.querySelectorAll('.update-btn').forEach(button => {
    button.addEventListener('click', (e) => {
        const studentId = e.target.getAttribute('data-id');
        fetch(`get_student_data.php?id=${studentId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('update_student_id').value = data.id;
                document.getElementById('update_student_name').value = data.name;
                document.getElementById('update_contact_number').value = data.contact_number;
                document.getElementById('update_gender').value = data.gender;
                document.getElementById('update_address').value = data.address;
                document.getElementById('update_birth_date').value = data.birth_date;
                document.getElementById('update_course').value = data.course;
                document.getElementById('update_year').value = data.year;
                document.getElementById('update_block').value = data.block;
                document.getElementById('update_status').value = data.status;

                document.getElementById('updateStudentModal').style.display = 'block';
            })
            .catch(error => console.error('Error fetching student data:', error));
    });
});

window.addEventListener('click', (e) => {
    if (e.target === document.getElementById('addStudentModal')) {
        document.getElementById('addStudentModal').style.display = 'none';
    } 
    if (e.target === document.getElementById('updateStudentModal')) {
        document.getElementById('updateStudentModal').style.display = 'none';
    }
});