{"changed":true,"filter":false,"title":"2020_04_20_104733_create_reserv_table.php","tooltip":"/airstyle/database/migrations/2020_04_20_104733_create_reserv_table.php","value":"<?php\n\nuse Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nclass CreateReservTable extends Migration\n{\n    /**\n     * Run the migrations.\n     *\n     * @return void\n     */\n    public function up()\n    {\n        Schema::create('reserv', function (Blueprint $table) {\n            $table->id();\n            $table->foreignId('shop_id')->constrained()->onDelete('cascade');\n            $table->foreignId('user_id')->constrained()->onDelete('cascade');\n            $table->integer('people_number');\n            $table->date('start_date');\n            $table->date('end_date');\n            $table->timestamps();\n\n            $table->dropForeign('reserv_shop_id_foreign');\n            $table->dropForeign('reserv_user_id_foreign');\n        });\n    }\n\n    /**\n     * Reverse the migrations.\n     *\n     * @return void\n     */\n    public function down()\n    {\n        Schema::dropIfExists('reserv');\n    }\n}\n","undoManager":{"mark":3,"position":3,"stack":[[{"start":{"row":17,"column":33},"end":{"row":18,"column":0},"action":"insert","lines":["",""],"id":4},{"start":{"row":18,"column":0},"end":{"row":18,"column":12},"action":"insert","lines":["            "]}],[{"start":{"row":18,"column":12},"end":{"row":18,"column":57},"action":"insert","lines":["$table->dropForeign('posts_user_id_foreign');"],"id":5}],[{"start":{"row":17,"column":33},"end":{"row":18,"column":0},"action":"insert","lines":["",""],"id":6},{"start":{"row":18,"column":0},"end":{"row":18,"column":12},"action":"insert","lines":["            "]}],[{"start":{"row":16,"column":0},"end":{"row":19,"column":57},"action":"remove","lines":["            $table->id();","            $table->timestamps();","            ","            $table->dropForeign('posts_user_id_foreign');"],"id":7},{"start":{"row":16,"column":0},"end":{"row":25,"column":58},"action":"insert","lines":["            $table->id();","            $table->foreignId('shop_id')->constrained()->onDelete('cascade');","            $table->foreignId('user_id')->constrained()->onDelete('cascade');","            $table->integer('people_number');","            $table->date('start_date');","            $table->date('end_date');","            $table->timestamps();","","            $table->dropForeign('reserv_shop_id_foreign');","            $table->dropForeign('reserv_user_id_foreign');"]}],[{"start":{"row":24,"column":0},"end":{"row":25,"column":58},"action":"remove","lines":["            $table->dropForeign('reserv_shop_id_foreign');","            $table->dropForeign('reserv_user_id_foreign');"],"id":8},{"start":{"row":23,"column":0},"end":{"row":24,"column":0},"action":"remove","lines":["",""]}]]},"ace":{"folds":[],"scrolltop":0,"scrollleft":0,"selection":{"start":{"row":23,"column":0},"end":{"row":23,"column":0},"isBackwards":false},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":0},"timestamp":1587382505471}