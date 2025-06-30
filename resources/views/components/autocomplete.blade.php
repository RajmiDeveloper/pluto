@props([
    'id','name','valueName','label','placeholder',
    'route','labelField','valueField','extraField'
])

<div {{ $attributes->merge(['class'=>'autocomplete-wrapper']) }}
     data-autocomplete
     data-route="{{ route($route) }}"
     data-label-field="{{ $labelField }}"
     data-value-field="{{ $valueField }}"
     data-extra-field="{{ $extraField }}"
     style="position: relative; display:inline-block; width:300px;">
    <label for="{{ $id }}">{{ $label }}</label><br>
    <input type="text"
           id="{{ $id }}"
           name="{{ $name }}"
           placeholder="{{ $placeholder }}"
           autocomplete="off"
           class="autocomplete-input contenedor"
           data-extra=0
           style="width:100%; padding:.5em; box-sizing:border-box; border:1px solid #ccc;" />
    <input type="hidden"
           id="{{ $valueName }}"
           name="{{ $id }}_value[]"
           class="autocomplete-value" />
    <input type="hidden"
           id="{{ $valueName }}"
           name="{{ $id }}_id[]"
           class="autocomplete-id" />
    <ul class="autocomplete-suggestions"
        style="
          position:absolute;
          top:calc(100% + 4px);
          left:0;
          width:100%;
          margin:0;
          padding:0;
          list-style:none;
          border:1px solid #ccc;
          background:#fff;
          box-sizing:border-box;
          display:none;
          max-height:200px;
          overflow-y:auto;
          z-index:1000;
        ">
    </ul>
</div>

<script>
(function(){
  document.addEventListener('input', function(e) {
    const input = e.target;
    if (!input.classList.contains('autocomplete-input')) return;
    const wrapper = input.closest('[data-autocomplete]');
    const route = wrapper.dataset.route;
    const labelField = wrapper.dataset.labelField;
    const valueField = wrapper.dataset.valueField;
    const extraField = wrapper.dataset.extraField;
    const hidden = wrapper.querySelector('.autocomplete-id');
    const hidden2 = wrapper.querySelector('.autocomplete-value');
    const sugg = wrapper.querySelector('.autocomplete-suggestions');
    const term = input.value.trim();
    if (term.length < 1) { sugg.style.display = 'none'; return; }
    fetch(route + '?term=' + encodeURIComponent(term))
      .then(res => res.json())
      .then(data => {
        sugg.innerHTML = '';
        if (!data.length) {
          const li = document.createElement('li');
          li.textContent = 'sin resultados';
          li.style.padding = '.5em';
          li.style.cursor = 'default';
          sugg.appendChild(li);
        } else {
          data.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item[labelField];
            li.dataset.value = item[valueField];
            if (extraField) li.dataset.extra = item[extraField];
            li.style.padding = '.5em';
            li.style.cursor = 'pointer';
            li.addEventListener('click', function() {
              input.value = this.textContent;
              hidden.value = this.dataset.value;
              hidden.dispatchEvent(new Event('change'));
              hidden2.value = this.dataset.extra;
              hidden2.dispatchEvent(new Event('change'));
              if (this.dataset.extra !== undefined) {
                input.dataset.extra = this.dataset.extra;
                input.dispatchEvent(new Event('extra'));
              }
              sugg.style.display = 'none';
            });
         li.classList.add(`autocomplete-option-${extraField}`)
            sugg.appendChild(li);
          });
        }
        sugg.style.display = 'block';
      })
      .catch(() => {
        sugg.innerHTML = '<li style="padding:.5em;color:red;">error al buscar</li>';
        sugg.style.display = 'block';
      });
  });

  document.addEventListener('focus', function(e) {
    const input = e.target;
    if (!input.classList.contains('autocomplete-input')) return;
    input.dispatchEvent(new Event('input'));
  }, true);

  document.addEventListener('click', function(e) {
    document.querySelectorAll('.autocomplete-suggestions').forEach(sugg => {
      if (!sugg.closest('[data-autocomplete]').contains(e.target)) {
        sugg.style.display = 'none';
      }
      
    });
  });
})();
</script>
