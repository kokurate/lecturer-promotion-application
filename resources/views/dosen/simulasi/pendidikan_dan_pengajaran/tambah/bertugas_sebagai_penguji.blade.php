<h5 class="text-center" style="color:#012970;" ><strong>Bertugas Sebagai</strong></h5>
    <div class="list-group">
        <div class="list-group-item">
            <div class="form-check">
                <input class="form-check-input" id="a_ketua_penguji" type="radio" name="penguji_uiian_akhir"  value="II.E.1"
                    @if(old('penguji_uiian_akhir') == 'II.E.1') checked @endif
                > 
                <label class="form-check-label" for="a_ketua_penguji">Ketua Penguji</label>
            </div>
        </div>
        <div class="list-group-item">
            <div class="form-check">
                <input class="form-check-input" id="b_anggota_penguji"type="radio" name="penguji_uiian_akhir" value="II.E.2"
                    @if(old('penguji_uiian_akhir') == 'II.E.2') checked @endif
                > 
                <label class="form-check-label" for="b_anggota_penguji">Anggota Penguji</label>

            </div>
        </div>
    </div>