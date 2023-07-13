<?php

namespace Database\Seeders;

use App\Models\ClassStaffing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ClassStaffingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $classIds = [
            141, 142, 193, 198, 201, 205, 207, 211, 213, 251, 270, 271, 274, 275, 283, 286, 318, 335, 336, 339, 348, 353, 356, 357, 359,
            360, 361, 362, 381, 383, 401, 403, 411, 415, 425, 429, 430, 432, 434, 437, 455, 459, 465, 466, 499, 501, 502, 503, 504,
            509, 510, 511, 512, 513, 514, 515, 516, 518, 519, 520, 521, 527, 529, 533, 534, 542, 543, 544, 546, 564, 569, 572, 573,
            575, 577, 578, 579, 581, 582, 583, 584, 585, 586, 587, 588, 589, 590, 591, 592, 593, 594, 595, 599, 600, 602, 603, 611,
            612, 613, 614, 615, 616, 617, 619, 622, 623, 624, 625, 626, 627, 628, 633, 634, 640, 642, 643, 644, 645, 646, 647, 648,
            651, 652, 653, 657, 659, 660, 661, 662, 664, 666, 667, 670, 671, 679, 683, 684, 685, 686, 692, 693, 694, 695, 696, 697,
            698, 699, 700, 702, 703, 704, 705, 707, 708, 709, 711, 713, 721, 723, 724, 725, 726, 727, 731, 732, 734, 736, 737, 739,
            740, 742, 743, 744, 746, 747, 748, 749, 750, 751, 752, 753, 754, 755, 756, 758, 759, 760, 761, 762, 763, 764, 765, 766,
            767, 768, 769, 773, 774, 775, 776, 777, 778, 779, 780, 782, 784, 786, 788, 789, 791, 792, 793, 794, 795, 796, 797, 799,
            800, 801, 802, 803, 804, 805, 806, 807, 808, 809, 810, 811, 812, 813, 814, 815, 816, 817, 818, 819, 820, 821, 822, 823,
            824, 825, 826, 827, 828, 829, 830, 831, 832, 833, 834, 835, 836, 837, 838, 839, 840, 841, 842, 843, 844, 845, 846, 847,
            848, 849, 850, 851, 852, 853, 854, 855, 856, 857, 858, 860, 862, 863, 865, 866, 867, 868, 869, 870, 871, 872, 873, 874,
            875, 876, 877, 878, 879, 880, 881, 882, 883, 884, 886, 887, 888, 889, 890, 891, 892, 893, 894, 895, 898, 899, 900, 901,
            902, 903, 904, 905, 906, 907, 908, 909, 910, 911, 912, 913, 914, 915, 920, 924, 925, 926, 927, 928, 929, 930, 937, 938,
            939, 940, 941, 943, 944, 947, 948, 949, 950, 951, 952, 953, 954, 955, 956, 957, 958, 959, 960, 961, 962, 963, 964, 967,
            968, 969, 970, 971, 972, 973, 974, 975, 976, 977, 978, 979, 980, 981, 982, 983, 984, 985, 986, 987, 988, 989, 990, 991,
            992, 993, 994, 995, 996, 997, 998, 999, 1000, 1001, 1002, 1003, 1008, 1009, 1010, 1011, 1012, 1018, 1019, 1045,
        ];

        foreach ($classIds as $classId) {
            if (!ClassStaffing::where('classes_id', $classId)->exists()) {
                $classStaffing = new ClassStaffing();
                $classStaffing->classes_id = $classId;
                $classStaffing->show_ups_internal = '0';
                $classStaffing->show_ups_external = '0';
                $classStaffing->show_ups_total = '0';
                $classStaffing->deficit = '0';
                $classStaffing->percentage = '0';
                $classStaffing->status = '0';
                $classStaffing->day_1 = '0';
                $classStaffing->day_2 = '0';
                $classStaffing->day_3 = '0';
                $classStaffing->day_4 = '0';
                $classStaffing->day_5 = '0';
                $classStaffing->day_6 = '0';
                $classStaffing->total_endorsed = '0';
                $classStaffing->internals_hires = '0';
                $classStaffing->additional_extended_jo = '0';
                $classStaffing->with_jo = '0';
                $classStaffing->pending_jo = '0';
                $classStaffing->pending_berlitz = '0';
                $classStaffing->pending_ov = '0';
                $classStaffing->pending_pre_emps = '0';
                $classStaffing->classes_number = '0';
                $classStaffing->pipeline_total = '0';
                $classStaffing->pipeline_target = '0';
                $classStaffing->cap_starts = '0';
                $classStaffing->internals_hires_all = '0';
                $classStaffing->pipeline = '0';
                $classStaffing->additional_remarks = '0';
                $classStaffing->transaction = 'Add Class Staffing';
                $classStaffing->active_status = 1;
                $classStaffing->save();

                $classStaffing->class_staffing_id = $classStaffing->id;
                $classStaffing->save();
            }
        }
        Schema::enableForeignKeyConstraints();}
}
