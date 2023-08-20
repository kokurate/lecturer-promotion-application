 <!-- Pertama Keluar yang Radio -->
<div class="col my-3">
    <p class="text-center mb-1" style="color:#012970;"><strong >Pilih salah satu komponen kegiatan</strong></p>
    <br>

    <div class="list-group">
        <div class="list-group-item">
            <div class="form-check">
                <input type="hidden" name="sks_now" value="{{ $sks_now }}">
                <input class="form-check-input" id="a_p1" type="radio" name="radio_option_2"  value="a" onclick="showInputMembimbing('a')"
                    @if(old('radio_option_2') == 'a') checked @endif
                > 
                <label class="form-check-label" for="a_p1">Pembimbing Utama per orang (setiap mahasiswa)</label>
            </div>
        </div>
        <div class="list-group-item">
            <div class="form-check">
                <input class="form-check-input" id="b_p2"type="radio" name="radio_option_2" value="b" onclick="showInputMembimbing('b')"
                    @if(old('radio_option_2') == 'b') checked @endif
                > 
                <label class="form-check-label" for="b_p2">Pembimbing Pendamping/Pembantu per orang (setiap mahasiswa)</label>

            </div>
        </div>
    </div>
        
        <br>
        
    <!-- Radio 1 -->
    <div id="pembimbing1" style="display: none;">
        <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Pembimbing Utama</strong></p>

        <div class="list-group">
            <div class="list-group-item">
                <div class="form-check">
                    <input class="form-check-input" id="pembimbing1_disertasi" type="radio" name="name_pembimbing1" value="II.D.1.a"
                        @if(old('name_pembimbing1') == 'II.D.1.a') checked @endif
                    > 
                    <label class="form-check-label" for="pembimbing1_disertasi">Disertasi</label>
                </div>
            </div>
            <div class="list-group-item">
                <div class="form-check">
                    <input class="form-check-input" id="pembimbing1_tesis"type="radio" name="name_pembimbing1" value="II.D.1.b"
                        @if(old('name_pembimbing1') == 'II.D.1.b') checked @endif
                    > 
                    <label class="form-check-label" for="pembimbing1_tesis">Tesis</label>
                </div>
            </div>
            <div class="list-group-item">
                <div class="form-check">
                    <input class="form-check-input" id="pembimbing1_skripsi"type="radio" name="name_pembimbing1" value="II.D.1.c"
                        @if(old('name_pembimbing1') == 'II.D.1.a.c') checked @endif
                    > 
                    <label class="form-check-label" for="pembimbing1_skripsi">Skripsi</label>
                </div>
            </div>
            <div class="list-group-item">
                <div class="form-check">
                    <input class="form-check-input" id="pembimbing1_las"type="radio" name="name_pembimbing1" value="II.D.1.d"
                        @if(old('name_pembimbing1') == 'II.D.1.d') checked @endif
                    > 
                    <label class="form-check-label" for="pembimbing1_las">Laporan Akhir Studi</label>
                </div>
            </div>
        </div> <!-- END list-group -->
    </div> <!-- End id pembimbing1 -->
    
    <!-- Radio 2 -->
    <div id="pembimbing2" style="display: none;">
        <p class="mt-2 mb-3 text-center" style="color:#012970;"><strong>Pembimbing Pendamping</strong></p>
        <div class="list-group">
            <div class="list-group-item">
                <div class="form-check">
                    
                    <input class="form-check-input" id="pembimbing2_disertasi" type="radio" name="name_pembimbing2" value="II.D.2.a"
                        @if(old('name_pembimbing2') == 'II.D.2.a') checked @endif
                    > 
                    <label class="form-check-label" for="pembimbing2_disertasi">Disertasi</label>
                </div>
            </div>
            <div class="list-group-item">
                <div class="form-check">
                    <input class="form-check-input" id="pembimbing2_tesis"type="radio" name="name_pembimbing2" value="II.D.2.b"
                        @if(old('name_pembimbing2') == 'II.D.2.b') checked @endif
                    > 
                    <label class="form-check-label" for="pembimbing2_tesis">Tesis</label>
                </div>
            </div>
            <div class="list-group-item">
                <div class="form-check">
                    <input class="form-check-input" id="pembimbing2_skripsi"type="radio" name="name_pembimbing2" value="II.D.2.c"
                        @if(old('name_pembimbing2') == 'II.D.2.c') checked @endif
                    > 
                    <label class="form-check-label" for="pembimbing2_skripsi">Skripsi</label>
                </div>
            </div>
            <div class="list-group-item">
                <div class="form-check">
                    <input class="form-check-input" id="pembimbing2_las"type="radio" name="name_pembimbing2" value="II.D.2.d"
                        @if(old('name_pembimbing2') == 'II.D.2.d') checked @endif
                    > 
                    <label class="form-check-label" for="pembimbing2_las">Laporan Akhir Studi</label>
                </div>
            </div>
        </div> <!-- END list-group -->
    </div> <!-- End id pembimbing2 -->
</div> <!-- End Col my-3 -->