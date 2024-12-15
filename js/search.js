document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const suggestionsBox = document.getElementById("suggestionsBox");
    const searchBtn = document.getElementById("searchBtn");

    let activeIndex = -1;

    const highlightText = (text, term) => {
        const regex = new RegExp(`(${term})`, 'gi');
        return text.replace(regex, "<strong>$1</strong>");
    };

    searchInput.addEventListener("input", async () => {
        const searchTerm = searchInput.value.trim();
        activeIndex = -1;

        if (searchTerm.length >= 2) {
            const response = await fetch(`search_suggestions.php?term=${searchTerm}`);
            const suggestions = await response.json();

            if (suggestions.length > 0) {
                suggestionsBox.innerHTML = suggestions.map(suggestion => 
                    `<div class="suggestion-item" data-id="${suggestion.id}">
                        ${highlightText(suggestion.name, searchTerm)}
                    </div>`
                ).join('');
                suggestionsBox.style.display = 'block';
            } else {
                suggestionsBox.innerHTML = `<div class="suggestion-item">No results found</div>`;
                suggestionsBox.style.display = 'block';
            }
        } else {
            suggestionsBox.style.display = 'none';
        }
    });

    searchInput.addEventListener("keydown", (e) => {
        const items = document.querySelectorAll(".suggestion-item");
        if (e.key === "ArrowDown") {
            activeIndex = (activeIndex + 1) % items.length;
            setActive(items);
        } else if (e.key === "ArrowUp") {
            activeIndex = (activeIndex - 1 + items.length) % items.length;
            setActive(items);
        } else if (e.key === "Enter" && activeIndex >= 0) {
            items[activeIndex].click();
        }
    });

    const setActive = (items) => {
        items.forEach((item, index) => {
            item.classList.toggle("active", index === activeIndex);
        });
    };

    function renderSuggestions(suggestions) {
    const suggestionsBox = document.getElementById('suggestionsBox');
    suggestionsBox.innerHTML = ''; // Clear old suggestions

    if (suggestions.length > 0) {
        suggestionsBox.style.display = 'block';
        suggestions.forEach(suggestion => {
            const suggestionItem = document.createElement('div');
            suggestionItem.className = 'suggestion-item';
            suggestionItem.textContent = suggestion.student_name;
            suggestionItem.onclick = function () {
                openStudentProfile(suggestion.students_id); // Open modal with ID
            };
            suggestionsBox.appendChild(suggestionItem);
        });
    } else {
        suggestionsBox.style.display = 'none';
    }
}

    searchBtn.addEventListener("click", () => {
        const searchTerm = searchInput.value.trim();
        if (searchTerm) {
            window.location.href = `admin_page.php?search=${searchTerm}`;
        }
    });

    document.addEventListener("click", (e) => {
        if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.style.display = 'none';
        }
    });
});