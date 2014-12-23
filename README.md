Miner
=====
Miner is a composer wrapper which brings composer to PocketMine plugin development. It allows developers to keep everything inside the `src` folder and not have to use `vendor` and register an additional autoloader. Miner moves stuff from `vendor` to the right place in `src` so your composer dependencies can piggyback the PocketMine class loader.

Miner doesn't have much of a point. You can just modify the PocketMine class loader to include the `vendor` folder. I created it because I wanted desperately to contain all my source in `src`, but I was having to copy and paste whenever I wanted to update.

### Composer.json "protection"
It would be suboptimal if people ran `composer install` without the wrapper. If you want to minimize the chance of this happening, you can add `#miner` to the very beginning of the `composer.json` on its own line. This will make the JSON invalid so composer will throw errors when using it, but Miner will fix the JSON before it is sent to composer.

### Using Miner
Using miner is easy. Just download composer and install