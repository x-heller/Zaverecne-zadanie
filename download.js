window.onload = function () {
    document.getElementById("downloadsk")
        .addEventListener("click", () => {
            const downloads = this.document.getElementById("downloads");
            console.log(downloads);
            console.log(window);
            var opt = {
                margin: 1,
                fontSize: 1,
                filename: 'infopage.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2},
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().from(downloads).set(opt).save();
        })
    document.getElementById("downloadeng")
        .addEventListener("click", () => {
            const downloade = this.document.getElementById("downloade");
            console.log(downloade);
            console.log(window);
            var opt = {
                margin: 1,
                fontSize: 1,
                filename: 'infopage.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2},
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().from(downloade).set(opt).save();
        })
}

