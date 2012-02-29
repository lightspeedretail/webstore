
<fieldset class="xlsfset">
    <legend> <?= _sp($_CONTROL->Name) ?> </legend>

    <div class="left">
        <?php
            if ($_CONTROL->Enabled)
                if ($_CONTROL->PriceControl)
                    $_CONTROL->PriceControl->RenderAsDefinition();
        ?>
    </div>

    <div class="left">
        <?php 
            if ($_CONTROL->ModuleControl) 
                $_CONTROL->ModuleControl->RenderAsDefinition();
        ?>
    </div>

    <div class="left clear">
        <?php 
            if ($_CONTROL->MethodControl) {
                $_CONTROL->MethodControl->AutoRenderChildren = true;
                $_CONTROL->MethodControl->RenderAsDefinition();
            }
        ?>
    </div>

    <div class="left clear">
        <dl>
            <dt>
                <?php if ($_CONTROL->ValidationError): ?>
                <span class="warning">
                    <?= $_CONTROL->ValidationError ?>
                </span>
                <?php else:
                    $_CONTROL->LabelControl->Render();
                endif;
                ?>
            </dt>
        </dl>
    </div>
</fieldset>

