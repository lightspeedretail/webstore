
<fieldset class="xlsfset">
    <legend> <?= _sp($_CONTROL->Name) ?> </legend>

    <div class="left">
        <?php 
            if ($_CONTROL->Module) 
                $_CONTROL->Module->RenderAsDefinition();
        ?>
    </div>

    <div class="left clear">
        <?php 
            if ($_CONTROL->Method) 
                $_CONTROL->Method->RenderAsDefinition();
        ?>
    </div>

    <div class="left clear">
        <dl>
            <dt>
                <?php
                if ($_CONTROL->Enabled) {
                    $_CONTROL->Price->Render();

                    if ($_CONTROL->Label->Visible)
                        print("&nbsp;&ndash;&nbsp;");
                }
    
                $_CONTROL->Label->Render();
                ?>
                <?php if ($_CONTROL->ValidationError): ?>
                <br>
                <span class="warning">
                    <?= $_CONTROL->ValidationError ?>
                </span>
                <?php
                endif;
                ?> 
            </dt>
        </dl>
    </div>
</fieldset>

