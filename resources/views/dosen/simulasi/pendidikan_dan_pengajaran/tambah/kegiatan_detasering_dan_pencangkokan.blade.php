                                    <p class="text-center" style="color:#ff0000;" ><strong>Dipilih</strong></p>
                                    <p class="text-center mt-0 mb-0" style="color:#012970;">
                                        <strong>
                                            Melaksanakan kegiatan detasering dan pencangkokan 
                                            di luar institusi tempat bekerja setiap semester 
                                        </strong>
                                        <p class="text-center mt-0 mb-0" style="color:#012970;"><strong>(bagi dosen Lektor kepala ke atas)</strong></p>
                                    </p>
                                    <h5 class="text-center" style="color:#012970;" ><strong>Pilih Kegiatan</strong></h5>
                                        <div class="list-group">
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="a_detasering" type="radio" name="kegiatan_detasering_dan_pencangkokan"  value="II.L.1"
                                                        @if(old('kegiatan_detasering_dan_pencangkokan') == 'II.L.1') checked @endif
                                                    > 
                                                    <label class="form-check-label" for="a_detasering">Detasering</label>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="b_pencangkokan"type="radio" name="kegiatan_detasering_dan_pencangkokan" value="II.L.2"
                                                        @if(old('kegiatan_detasering_dan_pencangkokan') == 'II.L.2') checked @endif
                                                    > 
                                                    <label class="form-check-label" for="b_pencangkokan">Pencangkokan</label>

                                                </div>
                                            </div>
                                        </div>