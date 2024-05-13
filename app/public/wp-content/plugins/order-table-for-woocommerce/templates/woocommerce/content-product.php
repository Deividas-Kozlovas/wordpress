<?php

htmlentities(!is_null(OTFW::instance()->loop->the_product()) ? OTFW::instance()->loop->the_product() : '');
