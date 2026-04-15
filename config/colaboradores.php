<?php

return [

    'certificacao_dias_alerta' => (int) env('COLAB_CERT_DIAS_ALERTA', 30),

    /** Dias mínimos entre alertas repetidos (e-mail / notificação) para o mesmo documento */
    'certificacao_intervalo_alerta_dias' => (int) env('COLAB_CERT_INTERVALO_DIAS', 7),

    /** true = também envia e-mail (configure MAIL_* no .env) */
    'notificar_email_certificacao' => filter_var(env('COLAB_CERT_NOTIFICAR_EMAIL', false), FILTER_VALIDATE_BOOLEAN),

];
