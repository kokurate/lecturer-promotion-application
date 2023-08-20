<p class="text-center" style="color:#ff0000;" ><strong>Dipilih</strong></p>
<p class="text-center" style="color:#012970;">
    <strong>
        Mengembangkan bahan pengajaran/bahan kuliah yang mempunyai nilai kebaharuan (setiap produk)
    </strong>
</p>
<p class="text-center" style="color:#012970;" ><strong>Pilih produk yang dihasilkan</strong></p>
    <div class="list-group">
        <div class="list-group-item">
            <div class="form-check">
                <input class="form-check-input" id="a_buku" type="radio" name="mengembangkan_bahan_pengajaran"  value="II.H.1"
                    @if(old('mengembangkan_bahan_pengajaran') == 'II.H.1') checked @endif
                > 
                <label class="form-check-label" for="a_buku">Buku Ajar</label>
            </div>
        </div>
        <div class="list-group-item">
            <div class="form-check">
                <input class="form-check-input" id="b_diklat"type="radio" name="mengembangkan_bahan_pengajaran" value="II.H.2"
                    @if(old('mengembangkan_bahan_pengajaran') == 'II.H.2') checked @endif
                > 
                <label class="form-check-label" for="b_diklat">
                    Diklat, Modul, Petunjuk	praktikum, Model, Alat bantu, Alat visual, Naskah tutorial,
                    Job sheet praktikum terkait dengan mata kuliah yang dilampau
                </label>

            </div>
        </div>
    </div>