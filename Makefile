RUNTIME = $(shell which php)
XDEBUG_MODE = 'xdebug.mode=profile'
XDEBUG_PROFILER_ENABLE_TRIGGER = 'xdebug.profiler_enable_trigger=1'
XDEBUG_OUTPUT_DIR = 'xdebug.output_dir=/tmp/xdebug'
XDEBUG_PROFILER_OUTPUT_NAME = 'xdebug.profiler_output_name=cachegrind.out.%R'
XDEBUG_USE_COMPRESSION = 'xdebug.use_compression=false'

runprof:
	$(RUNTIME) -d $(XDEBUG_MODE) -d $(XDEBUG_PROFILER_ENABLE_TRIGGER) \
		-d $(XDEBUG_OUTPUT_DIR) -d $(XDEBUG_PROFILER_OUTPUT_NAME) \
		-d $(XDEBUG_USE_COMPRESSION) -S localhost:31337 -t ./public

run:
	$(RUNTIME) -S localhost:31337 -t ./public

analyze:
	./vendor/bin/psalm --force-jit 2>&1

.PHONY: runprof run analyze
