
document.addEventListener('DOMContentLoaded', () => {
    // Event listeners dipasang sekali di awal
    document.getElementById('jumlah_individu').addEventListener('input', hitungZakatFitrah);
    document.getElementById('harga_beras').addEventListener('input', hitungZakatFitrah);
    document.getElementById('Penghasilan').addEventListener('input', hitungZakatPenghasilan);
    document.getElementById('persentase_zakat').addEventListener('input', hitungZakatPenghasilan);
    document.getElementById('jumlah_ternak').addEventListener('input', hitungZakatTernak);
    document.getElementById('jenis_ternak').addEventListener('change', hitungZakatTernak);

    document.getElementById('jenis_zakat').addEventListener('change', toggleJenisZakat);

    toggleJenisZakat(); // Langsung jalankan saat halaman dimuat
});

function toggleJenisZakat() {
    const select = document.getElementById('jenis_zakat');
    const selectedOption = select.options[select.selectedIndex];
    const zakatNama = selectedOption.getAttribute('data-nama')?.toLowerCase() || '';

    const jumlahZakatGroup = document.getElementById('jumlah-zakat-group');
    const penghasilanGroup = document.getElementById('penghasilan-group');
    const ternakGroup = document.getElementById('ternak-group');

    // Utility: sembunyikan dan disable semua input
    function hideAndDisable(group) {
        group.style.display = 'none';
        group.querySelectorAll('input, select').forEach(el => el.disabled = true);
    }

    // Utility: tampilkan dan enable
    function showAndEnable(group) {
        group.style.display = 'block';
        group.querySelectorAll('input, select').forEach(el => el.disabled = false);
    }

    // Reset semua
    hideAndDisable(jumlahZakatGroup);
    hideAndDisable(penghasilanGroup);
    hideAndDisable(ternakGroup);

    // Tampilkan sesuai pilihan
    if (zakatNama.includes('fitrah')) {
        showAndEnable(jumlahZakatGroup);
        hitungZakatFitrah();
    } else if (zakatNama.includes('penghasilan') || zakatNama.includes('mal')) {
        showAndEnable(penghasilanGroup);
        hitungZakatPenghasilan();
    } else if (zakatNama.includes('ternak')) {
        showAndEnable(ternakGroup);
        hitungZakatTernak();
    }
}

function hitungZakatFitrah() {
    const jumlahIndividu = parseFloat(document.getElementById('jumlah_individu').value) || 0;
    const hargaBeras = parseFloat(document.getElementById('harga_beras').value) || 0;
    const zakat = jumlahIndividu * hargaBeras;
    document.getElementById('jumlah_zakat2').value = zakat.toFixed(2);
}

function hitungZakatPenghasilan() {
    const penghasilan = parseFloat(document.getElementById('Penghasilan').value) || 0;
    const persen = parseFloat(document.getElementById('persentase_zakat').value) || 0;
    const zakat = (penghasilan * persen) / 100;
    document.getElementById('Jumlah_Zakat_penghasilan').value = zakat.toFixed(2);
}

function hitungZakatTernak() {
    const jenis = document.getElementById('jenis_ternak').value;
    const jumlah = parseInt(document.getElementById('jumlah_ternak').value) || 0;
    let zakat = 0;

    if (jenis === "kambing") {
        if (jumlah >= 40 && jumlah < 121) zakat = 1;
        else if (jumlah >= 121 && jumlah < 201) zakat = 2;
        else if (jumlah >= 201) zakat = Math.floor(jumlah / 100);
    } else if (jenis === "sapi") {
        if (jumlah >= 30 && jumlah < 40) zakat = 1;
        else if (jumlah >= 40 && jumlah < 60) zakat = 1;
        else if (jumlah >= 60) zakat = Math.floor(jumlah / 30);
    } else if (jenis === "unta") {
        if (jumlah >= 30 && jumlah < 40) zakat = 1;
        else if (jumlah >= 40 && jumlah < 60) zakat = 2;
        else if (jumlah >= 60) zakat = Math.floor(jumlah / 40);
    }

    document.getElementById('Jumlah_Zakat_ternak').value = zakat;
}
