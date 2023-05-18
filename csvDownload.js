function downloadCSV() {
    var table = document.getElementById('myTable');
    var data = tableToCSV(table);

    var link = document.createElement('a');
    link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent('\uFEFF' + data.replace(/%20/g, ' '))); // Add the UTF-8 BOM character and replace %20 with spaces
    link.setAttribute('download', 'table.csv');
    link.style.display = 'none';

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function tableToCSV(table) {
    var rows = table.rows;
    var csvData = [];

    // Add table headers to CSV
    var headers = table.querySelectorAll('th');
    var headerData = [];
    for (var h = 0; h < headers.length; h++) {
        headerData.push(headers[h].textContent);
    }
    csvData.push(headerData.join(','));

    // Add table body rows to CSV
    for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].querySelectorAll('td');
        var rowData = [];

        for (var j = 0; j < cells.length; j++) {
            rowData.push(encodeURIComponent(cells[j].textContent));
        }

        csvData.push(rowData.join(','));
    }

    return csvData.join('\n');
}