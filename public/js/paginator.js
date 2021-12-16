function nextPage() {
    if (paginationOptions.currentPage < paginationOptions.lastPage) {
        paginationOptions.currentPage++;
        paginate();
    }
}

function prevPage() {
    if (paginationOptions.currentPage > 1) {
        paginationOptions.currentPage--;
        paginate();
    }
}

function search(elem) {

    if (!elem.key.includes("Arrow")){
        paginationOptions.searchTerm = elem.target.value;
        paginate();
    }
}

async function paginate() {

    document.getElementById('loading').style.display = 'block';

    return await fetch(`${paginationOptions.url}?page=${paginationOptions.currentPage}&search=${paginationOptions.searchTerm}&filters=${JSON.stringify(paginationOptions.filers)}`).then(response => {
        return response.json();
    }).then(json => {

        document.getElementById('loading').style.display = 'none';
        let template = Handlebars.compile(document.getElementById('row').innerText);

        if (json.data.length === 0)
            document.getElementById('pagination_target').innerHTML = document.getElementById('row_empty').innerText;
        else
            document.getElementById('pagination_target').innerHTML = template({data: json.data});

        paginationOptions.currentPage = json.current_page;
        paginationOptions.lastPage = json.last_page;
        document.getElementById('current_page').innerText = paginationOptions.currentPage;
        document.getElementById('last_page').innerText = paginationOptions.lastPage;
        return true;
    }).catch(error => {
        console.error(error);
        M.toast({html: "There was an error when fetching list. Please try again.", classes: "imperial-red"});
        return false;
    });
}
