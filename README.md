# Kalkulačka
Jednoduchá aplikace pro základní operace:
1. Součet
2. Odečet
3. Násobení
4. Dělení

## Implementace rozšířených funckcí
Aplkika si poradí se všemi matematickými operacemi, sama vyhodnotí, kterou operaci je potřebné udělat
jako první + řešení závorek. Taky nemá problém s desetinnými číslami. Aplikace umí vyhodnotit snad 
všechny edge casy, jako je zadáni nenumerického vstupu nebo dělení nulou. V souboru math.php je 
taky pár testovacích assertů. Aplikace bohužel neumí opakovat stejnou operaci, snažil jsem se o 
inplementaci pomocí session a taky cookies, ale vzhledem k času jsem od řešení ustoupil. 

## Jiné soubory
Použil jsem soubor style.css pro frontend aplikace, snažil jsem se o co největší podobu se zadáním.
Soubor script.js je čistě jen pro usnadnění práce s aplikací, v každém volání jenom přesune kurzor 
na konec inputu. 