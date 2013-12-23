#Dog-Cat-Example-Softdeletes

In this examples we have cat and dogs, both are animals. The structure is the following:

Animals->Cat
Animals->Dog

Every animal have the following attributes: "id", "name" (Animal.php).
Cat inherit from Animal and also have the attribute: "food"  (Cat.php).
Dog inhetit from Animal and also have the attribute: "collar" (Dog.php).

You can create an Animal, a Cat or a Dog; when you create/save/update them you always save data in the "Animal" table but fill only the columns that belongs to the given class. If you try to load data from "Cat" you will automatically search only on the records that are of "Cat" type. To understand how to setup that check the files Animal.php, Cat.php and Dog.php (are well commented). After you setted up you models you can use them normally as standard Eloquent models.



