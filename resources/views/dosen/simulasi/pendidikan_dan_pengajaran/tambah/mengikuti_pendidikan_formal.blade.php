<div class="col my-3">
    <p class="text-center" style="color:#012970;"><strong>Wajib diisi jika memilih Pendidikan Formal</strong></p>
    <div class="list-group">
        <div class="list-group-item">
            <div class="form-check">
                <input id="dkr" class="my-2" type="radio" name="jenis_pendidikan" value="doktor"
                    @if(old('jenis_pendidikan') == 'doktor') checked @endif>
                <label for="dkr">Doktor/sederajat</label>
            </div>
        </div>     
        <div class="list-group-item">
            <div class="form-check">
                <input id="mgr" class="my-2" type="radio" name="jenis_pendidikan" value="magister"
                    @if(old('jenis_pendidikan') == 'magister') checked @endif>
                <label for="mgr">Magister/sederajat</label>
            </div>
        </div>
    </div>  
</div>
