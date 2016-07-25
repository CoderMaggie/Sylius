.. index::
   single: Currencies

Currencies
==========

Sylius supports multiple currencies per channel and makes it very easy to manage them.

There are several approaches to processing several currencies, but we decided to use the simplest solution.
We are storing all money values in the **base currency** and convert them to other currencies with current rates or specific rates.

.. note::

    The **base currency** is set during the installation of Sylius and it has the **exchange rate** equal to "1.00".

.. tip::

    In the dev environment you can easily check the active and the base currencies in the Symfony debug toolbar:

    .. image:: ../_images/toolbar.png
        :align: center

Currency Context
----------------

By default, user can switch the current currency in the frontend of the store.

To manage the currently used currency, we use the **Currency Context**. You can always access it through the ``sylius.context.currency`` id.

.. code-block:: php

    <?php

    public function fooAction()
    {
        $currencyCode = $this->get('sylius.context.currency')->getCurrencyCode();
    }

Currency Converter
------------------

The **Currency Converter** is a service available under the ``sylius.currency_converter`` id.

It lets you to convert money values from the base currency to all the other currencies and backwards.

.. code-block:: php

    <?php

    public function fooAction()
    {
        // convert 100 of the base currency (for instance $1.00 if USD is your base) to PLN.
        $this->get('sylius.currency_converter')->convertFromBase(100, 'PLN');

        // or the other way - convert 1.00 PLN to amount in the base currency
        $this->get('sylius.currency_converter')->convertToBase(100, 'PLN');
    }

Currency Provider
-----------------

The default menu for selecting currency is using a service - **Currency Provider** - with the ``sylius.currency_provider`` id,
which returns all available currencies and the default one.
This is your entry point if you would like override this logic and return different currencies for various scenarios.

.. code-block:: php

    <?php

    public function fooAction()
    {
        $defaultCurrencyCode = $this->get('sylius.currency_provider')->getDefaultCurrencyCode();

        $currenciesCodes = $this->get('sylius.currency_provider')->getAvailableCurrenciesCodes();
    }

.. note::

    The default currency does not have to be your base currency.

Learn more
----------

* :doc:`Currency - Component Documentation </components/Currency/index>`
