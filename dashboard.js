window.onload = async () => {
    const resultContainer = document.querySelector('#results');
    const filters = document.querySelectorAll(".filters li");

    const loadContacts = async (filter = 'All') => {
        resultContainer.innerHTML = '';

        try {
            const response = await fetch(`dashboard.php?q=${encodeURIComponent(filter)}`);
            if (response.ok) {
                const contacts = await response.json();

                let tableHTML = '<table><thead><tr><th>Name</th><th>Email</th><th>Company</th><th>Type</th><th></th></tr></thead><tbody>';
                contacts.forEach(contact => {

                    let typeClass = '';
                    if (contact.type === 'Sales Lead') {
                        typeClass = 'type-indicator sales-lead';
                    } else if (contact.type === 'Support') {
                        typeClass = 'type-indicator support';
                    }

                    tableHTML += `
                        <tr>
                            <td class="name">${contact.name}</td>
                            <td>${contact.email}</td>
                            <td>${contact.company}</td>
                            <td><span class="${typeClass}">${contact.type}</span></td>
                            <td><a href="view_contact.php?id=${contact.id}" class="view-link">View</a></td>
                        </tr>
                    `;
                });
                tableHTML += '</tbody></table>';

                resultContainer.innerHTML = tableHTML;
            } else {
                throw new Error('Failed to fetch contacts');
            }
        } catch (error) {
            console.error('Error:', error);
            resultContainer.innerText = 'Failed to load contacts.';
        }
    };

    await loadContacts();

    filters.forEach(filter => {
        filter.addEventListener('click', async (event) => {
            event.preventDefault();
            const filterType = event.currentTarget.textContent.trim();

            filters.forEach(f => f.classList.remove('current'));

            event.currentTarget.classList.add('current');

            await loadContacts(filterType);
        });
    });
};
