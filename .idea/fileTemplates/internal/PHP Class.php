<?php
#parse("PHP File Header.php")

#if (${NAMESPACE})

namespace ${NAMESPACE};

#end

class ${NAME} {

    public function #[[$do$]]#() {
        return "some return";
    }
}