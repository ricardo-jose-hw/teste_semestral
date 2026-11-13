@props(['value' => 'email'])
<fieldset class="campo campo-full">
  <legend>Forma de acesso</legend>
  <div class="opcoes">
    <label><input type="radio" name="acesso" value="email" @checked(old('acesso', $value) === 'email')> E-mail e senha</label>
    <label><input type="radio" name="acesso" value="google" @checked(old('acesso', $value) === 'google')> Google</label>
  </div>
</fieldset>