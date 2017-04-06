<h2 class="form-signin-heading">JSON options:</h2>
<div class="checkbox">
  <label>
    <input type="checkbox" name="options[all]" value="all"
      @if(old('options.all') || is_null(old('options')))
        checked="" 
      @endif
    > Select all (default)
  </label><br>
  <label>
    <input type="checkbox" name="options[hex_tag]" value="hex_tag"
      @if(old('options.hex_tag'))
        checked="" 
      @endif
    > All < and > are converted to \u003C and \u003E.
  </label><br>
  <label>
    <input type="checkbox" name="options[hex_amp]" value="hex_amp"
      @if(old('options.hex_amp'))
        checked="" 
      @endif
    > All &s are converted to \u0026.
  </label><br>
  <label>
    <input type="checkbox" name="options[hex_apos]" value="hex_apos"
      @if(old('options.hex_apos'))
        checked="" 
      @endif
    > All ' are converted to \u0027.
  </label><br>              
  <label>
    <input type="checkbox" name="options[hex_quot]" value="hex_quot"
      @if(old('options.hex_quot'))
        checked="" 
      @endif
    > All " are converted to \u0022.
  </label><br>
  <label>
    <input type="checkbox" name="options[whitespace]" value="whitespace"
      @if(old('options.whitespace'))
        checked="" 
      @endif
    > Use whitespace in returned data to format it.
  </label><br>
  <label>
    <input type="checkbox" name="options[slashes]" value="slashes"
      @if(old('options.slashes'))
        checked="" 
      @endif
    > Don't escape /.
  </label><br>
  <label>
    <input type="checkbox" name="options[unicode]" value="unicode"
      @if(old('options.unicode'))
        checked="" 
      @endif
    > Encode multibyte Unicode characters literally (default is to escape as \uXXXX).
  </label><br>              
</div>
<input type="hidden" value="{{ $json }}" name="json">
<input type="submit" value="Apply" name="apply" class="btn btn-lg btn-primary btn-block">