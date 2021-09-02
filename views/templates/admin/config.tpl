<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item {if $check != 1}active{/if}" id="formulario">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Formulario</a>
    </li>
    <li class="nav-item {if $check == 1}active{/if}" id="tabla_li">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Tabla</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade {if $check != 1}active in{/if}" id="home" role="tabpanel" aria-labelledby="home-tab">{$formulario}</div>
    <div class="tab-pane fade {if $check == 1}active in{/if}" id="profile" role="tabpanel" aria-labelledby="profile-tab">{$lista}</div>
</div>



                       