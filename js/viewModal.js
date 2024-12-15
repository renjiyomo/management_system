function openStudentProfile(studentId) {

    fetch(`fetch_student.php?id=${studentId}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
    
                document.getElementById('profileName').textContent = data.student_name;
                document.getElementById('profileCourse').textContent = data.course;
                document.getElementById('profileYearBlock').textContent = data.year + data.block;
                document.getElementById('profileContact').textContent = data.contact_number;
                document.getElementById('profileAddress').textContent = data.address;
                document.getElementById('profileBirthDate').textContent = data.birth_date;
                document.getElementById('profileGender').textContent = data.gender;
                document.getElementById('profileStatus').textContent = data.status;
    
                document.getElementById('studentProfileModal').style.display = 'block';
            } else {
                alert('Student details not found.');
            }
        })
        .catch(error => {
            console.error('Error fetching student data:', error);
            alert('Failed to load student details.');
        });
    }
    
    
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('suggestion-item')) {
            const studentId = e.target.getAttribute('data-id');
            openStudentProfile(studentId);
        }
    });
    
    document.getElementById('closeProfileModal').addEventListener('click', function () {
    document.getElementById('studentProfileModal').style.display = 'none';
    });
    
    window.onclick = function (event) {
    const modal = document.getElementById('studentProfileModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
    }