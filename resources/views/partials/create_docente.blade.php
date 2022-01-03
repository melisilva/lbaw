<div class="modal fade" role="dialog" tabindex="-1" id="docente">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Docente</h4><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('new_teacher') }}">
                    @csrf
                    <div class="mb-3" style="margin-bottom: 0px;">
                    <div style="margin-bottom: 10px;">
                        <p style="margin-bottom: 3px;">Formação</p>
                        <input type="text" id="formation-input" name="formation" style="width: 100%;color: #0c1618;">
                    </div>
                    <div style="margin-bottom: 10px;">
                        <p style="margin-bottom: 3px;">Departamento</p>
                        <input type="text" id="department-input" name="department" style="width: 100%;color: #0c1618;">
                    </div>
                    <div class="modal-footer">
                <button class="btn btn-primary" data-bs-dismiss="modal" id="reg-submit" type="submit">Registar</button>
            </div>
                </form>
            </div>
            
        </div>
    </div>
</div>