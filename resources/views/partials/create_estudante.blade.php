<div class="modal fade" role="dialog" tabindex="-1" id="estudante">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Estudante</h4><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                 <form method="POST" action="{{ route('new_student') }}">
                    @csrf
                    <div class="mb-3">
                    <div style="margin-bottom: 10px;">
                        <p style="margin-bottom: 3px;">Curso</p><input type="text" id="course-input" name="course" style="width: 100%;color: #0c1618;">
                    </div>
                    <div style="margin-bottom: 10px;">
                        <p style="margin-bottom: 3px;">Ano Corrente</p><input type="range" class="form-control-range" name="year" id="year-range" default=1 min=1 max=5 onchange="document.getElementById('year').innerHTML = this.value;">
                        <output id="year">1</output>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <p style="margin-bottom: 3px;">Média</p><input type="range" class="form-control-range" name="media" id="avg-range" default=10.0 min=0.0 max=20.0 step=0.1 onchange="document.getElementById('avg').innerHTML = this.value;">
                        <output id="avg">10.0</output>
                    </div>
                    <div class="modal-footer">
                <button class="btn btn-primary" data-bs-dismiss="modal" id="reg-submit" type="submit">Registar</button>
            </div>
                </form>
            </div>
            
        </div>
    </div>
</div>