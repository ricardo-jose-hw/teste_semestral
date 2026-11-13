<div class="exame-linha">
  <label class="campo">
    <span class="rotulo">Exame</span>
    <input name="nome_exame[]" list="exames-sugeridos" maxlength="100" required>
  </label>
  <label class="campo">
    <span class="rotulo">Observações</span>
    <input name="obs_exame[]" maxlength="255">
  </label>
  <label class="campo">
    <span class="rotulo">Arquivo (PDF ou imagem)</span>
    <input name="arquivo_exame[]" type="file" accept=".pdf,image/*" required>
  </label>
  <button class="btn-icone perigo" type="button" data-remover-exame title="Remover exame" aria-label="Remover exame">
    <x-icone nome="lixo" />
  </button>
</div>
