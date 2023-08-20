<!-- Pertama Keluar yang Radio -->
<div class="col my-3">
    <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
    <br>

    <div class="list-group">
        <div class="list-group-item">
            <div class="form-check">
                <input type="hidden" name="sks_now" value="{{ $sks_now }}">
                <input class="form-check-input" id="a_perkuliahan" type="radio" name="radio_option_1"  value="a" onclick="showInput('a')"
                    @if(old('radio_option_1') == 'a') checked @endif
                > 
                <label class="form-check-label" for="a_perkuliahan">Perkuliahan</label>
            </div>
        </div>
        <div class="list-group-item">
            <div class="form-check">
                <input class="form-check-input" id="b_dokter_klinis"type="radio" name="radio_option_1" value="b" onclick="showInput('b')"
                    @if(old('radio_option_1') == 'b') checked @endif
                > 
                <label class="form-check-label" for="b_dokter_klinis">Kegiatan pelaksanaan pendidikan untuk pendidikan dokter klinis</label>

            </div>
        </div>
    </div>
        
        <br>
        
    <!-- Radio 1 -->
    <div id="perkuliahan" style="display: none;">
        <div class="col my-3">
            <label for="perkuliahan" class="mt-0 form-label" style="color:#012970;"><strong>SKS (Jika Kegiatan Perkuliahan)</strong></label>
            <input id="inputPerkuliahan" class="form-control{{ $errors->has('perkuliahan') ? ' is-invalid' : '' }}" type="text" name="perkuliahan" value="{{ old('perkuliahan') }}" placeholder="Contoh 2">
            {{-- <select name="perkuliahan" class="form-select">
                <option value="1" selected>1</option>
                <option value="2" >2</option>
                <option value="3" >3</option>
                <option value="4" >4</option>
                <option value="5" >5</option>
                <option value="6" >6</option>
                <option value="7" >7</option>
                <option value="8" >8</option>
                <option value="9" >9</option>
                <option value="10" >10</option>
                <option value="11" >11</option>
                <option value="12" >12</option>
            </select> --}}
            <label for=""  style="color:#012970;">SKS sekarang : {{ $sks_now }} Maksimal 12. </label>
            
        </div> 
    </div>
    
    <!-- Radio 2 -->
    <div id="dokter_klinis" style="display: none;">
        <input type="hidden" name="dokter_klinis_now" value="{{ $dokter_klinis_now }}">
        <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Pilih 1 poin yang ada di bawah ini</strong></p>
        <div class="list-group">
            <div class="list-group-item">
                <div class="form-check">
                    
                    <input class="form-check-input" id="dokter_klinis_1" type="radio" name="dokter_klinis" value="II.A.3.a"
                        @if(old('dokter_klinis') == 'II.A.3.a') checked @endif
                    > 
                    <label class="form-check-label" for="dokter_klinis_1">Melaksanakan Pengajaran Untuk Peserta Pendidikan Dokter Melalui Tindakan Medik Spesialistik</label>
                </div>
            </div>
            <div class="list-group-item">
                <div class="form-check">
                    <input class="form-check-input" id="dokter_klinis_2"type="radio" name="dokter_klinis" value="II.A.3.b" 
                        @if(old('dokter_klinis') == 'II.A.3.b') checked @endif
                    > 
                    <label class="form-check-label" for="dokter_klinis_2">Melakukan Pengajaran Konsultasi Spesialis Kepala Peserta Pendidikan Dokter</label>
                </div>
            </div>
            <div class="list-group-item">
                <div class="form-check">
                    <input class="form-check-input" id="dokter_klinis_3"type="radio" name="dokter_klinis" value="II.A.3.c" 
                        @if(old('dokter_klinis') == 'II.A.3.c') checked @endif
                    > 
                    <label class="form-check-label" for="dokter_klinis_3">Melakukan Pemeriksaan Luar Dengan Pembimbingan Terhadap Peserta Pendidikan Dokter</label>
                </div>
            </div>
            <div class="list-group-item">
                <div class="form-check">
                    <input class="form-check-input" id="dokter_klinis_4"type="radio" name="dokter_klinis" value="II.A.3.d" 
                        @if(old('dokter_klinis') == 'II.A.3.d') checked @endif
                    > 
                    <label class="form-check-label" for="dokter_klinis_4">Melakukan Pemeriksaan Dalam Dengan Pembimbingan Terhadap Peserta Pendidikan Dokter</label>
                </div>
            </div>
            <div class="list-group-item">
                <div class="form-check">
                    <input class="form-check-input" id="dokter_klinis_5"type="radio" name="dokter_klinis" value="II.A.3.e" 
                        @if(old('dokter_klinis') == 'II.A.3.e') checked @endif
                    > 
                    <label class="form-check-label" for="dokter_klinis_5">Menjadi Saksi Ahli Dengan Pembimbingan Terhadap Peserta Pendidikan Dokter</label>
                </div>
            </div>
        </div> <!-- END list-group -->
        


    </div>
</div>