########
# Math #
########

# See: https://www.cmcrossroads.com/article/learning-gnu-make-functions-arithmetic

# _int consists of 65536 x's built from the 16 x's in 16
_int_16 := x x x x x x x x x x x x x x x
_int := $(foreach a,$(_int_16),$(foreach b,$(_int_16),$(foreach c,$(_int_16),$(_int_16)))))

# _int_decode turns a number in x's representation into a integer for human
# consumption
_int_decode = $(words $(1))

# _int_encode takes an integer and returns the appropriate x's representation
# of the number by chopping $1 x's from the start of _int
_int_encode = $(if $(1),$(wordlist 1,$(1),$(_int)))

# Usage:
#   $(call manala_plus, 40, 2) = 42
manala_plus = $(call _int_decode,$(call _int_encode,$(strip $(1))) $(call _int_encode,$(strip $(2))))
