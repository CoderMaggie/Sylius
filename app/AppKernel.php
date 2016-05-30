<?php

use Sylius\Bundle\CoreBundle\Application\Kernel;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Gonzalo Vilaseca <gvilaseca@reiss.co.uk>
 */
class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = [
            new \AppBundle\AppBundle()
        ];

        return array_merge(parent::registerBundles(), $bundles);
    }
}

