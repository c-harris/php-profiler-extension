/*
 *  Copyright (c) 2009 Facebook
 *  Copyright (c) 2014-2016 Qafoo GmbH
 *  Copyright (c) 2016 Algoweb GmbH
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 *
 */

#ifndef PHP_ALGOWEB_H
#define PHP_ALGOWEB_H

extern zend_module_entry algoweb_module_entry;
#define phpext_algoweb_ptr &algoweb_module_entry

#ifdef PHP_WIN32
#define PHP_ALGOWEB_API __declspec(dllexport)
#else
#define PHP_ALGOWEB_API
#endif

#ifdef ZTS
#include "TSRM.h"
#endif

/* Algoweb version                           */
#define ALGOWEB_VERSION       "4.1.2"

/* Fictitious function name to represent top of the call tree. The paranthesis
 * in the name is to ensure we don't conflict with user function names.  */
#define ROOT_SYMBOL                "main()"

/* Size of a temp scratch buffer            */
#define SCRATCH_BUF_LEN            512

/* Hierarchical profiling flags.
 *
 * Note: Function call counts and wall (elapsed) time are always profiled.
 * The following optional flags can be used to control other aspects of
 * profiling.
 */
#define ALGOWEB_FLAGS_NO_BUILTINS   0x0001 /* do not profile builtins */
#define ALGOWEB_FLAGS_CPU           0x0002 /* gather CPU times for funcs */
#define ALGOWEB_FLAGS_MEMORY        0x0004 /* gather memory usage for funcs */
#define ALGOWEB_FLAGS_NO_USERLAND   0x0008 /* do not profile userland functions */
#define ALGOWEB_FLAGS_NO_COMPILE    0x0010 /* do not profile require/include/eval */
#define ALGOWEB_FLAGS_NO_SPANS      0x0020
#define ALGOWEB_FLAGS_NO_HIERACHICAL 0x0040

/* Constant for ignoring functions, transparent to hierarchical profile */
#define ALGOWEB_MAX_FILTERED_FUNCTIONS  256
#define ALGOWEB_FILTERED_FUNCTION_SIZE                           \
               ((ALGOWEB_MAX_FILTERED_FUNCTIONS + 7)/8)
#define ALGOWEB_MAX_ARGUMENT_LEN 256

#if !defined(uint64)
typedef unsigned long long uint64;
#endif
#if !defined(uint32)
typedef unsigned int uint32;
#endif
#if !defined(uint8)
typedef unsigned char uint8;
#endif

#if PHP_VERSION_ID < 70000
struct _zend_string {
  char *val;
  int   len;
  int   persistent;
};
typedef struct _zend_string zend_string;
typedef long zend_long;
typedef int strsize_t;
typedef zend_uint uint32_t;
#endif

/**
 * *****************************
 * GLOBAL DATATYPES AND TYPEDEFS
 * *****************************
 */

/* Algoweb maintains a stack of entries being profiled. The memory for the entry
 * is passed by the layer that invokes BEGIN_PROFILING(), e.g. the hp_execute()
 * function. Often, this is just C-stack memory.
 *
 * This structure is a convenient place to track start time of a particular
 * profile operation, recursion depth, and the name of the function being
 * profiled. */
typedef struct hp_entry_t {
    char                   *name_hprof;                       /* function name */
    int                     rlvl_hprof;        /* recursion level for function */
    uint64                  tsc_start;         /* start value for wall clock timer */
    uint64                  cpu_start;         /* start value for CPU clock timer */
    long int                mu_start_hprof;                    /* memory usage */
    long int                pmu_start_hprof;              /* peak memory usage */
    struct hp_entry_t      *prev_hprof;    /* ptr to prev entry being profiled */
    uint8                   hash_code;     /* hash_code for the function name  */
    long int                span_id; /* span id of this entry if any, otherwise -1 */
} hp_entry_t;

typedef struct hp_function_map {
    char **names;
    uint8 filter[ALGOWEB_FILTERED_FUNCTION_SIZE];
} hp_function_map;

typedef struct tw_watch_callback {
    zend_fcall_info fci;
    zend_fcall_info_cache fcic;
} tw_watch_callback;

/* Algoweb's global state.
 *
 * This structure is instantiated once.  Initialize defaults for attributes in
 * hp_init_profiler_state() Cleanup/free attributes in
 * hp_clean_profiler_state() */
ZEND_BEGIN_MODULE_GLOBALS(hp)

    /*       ----------   Global attributes:  -----------       */

    /* Indicates if Algoweb is currently enabled */
    int              enabled;

    /* Indicates if Algoweb was ever enabled during this request */
    int              ever_enabled;

    int              prepend_overwritten;

    /* Holds all the Algoweb statistics */
#if PHP_VERSION_ID >= 70000
    zval            stats_count;
    zval            spans;
    zval            exception;
#else
    zval            *stats_count;
    zval            *spans;
    zval            *exception;
#endif
    long            current_span_id;
    uint64          start_time;

    zval            *backtrace;

    /* Top of the profile stack */
    hp_entry_t      *entries;

    /* freelist of hp_entry_t chunks for reuse... */
    hp_entry_t      *entry_free_list;

    /* Function that determines the transaction name and callback */
    zend_string       *transaction_function;
    zend_string     *transaction_name;
    char            *root;

    zend_string     *exception_function;

    double timebase_factor;

    /* Algoweb flags */
    uint32 algoweb_flags;

    /* counter table indexed by hash value of function names. */
    uint8  func_hash_counters[256];

    /* Table of filtered function names and their filter */
    int     filtered_type; // 1 = blacklist, 2 = whitelist, 0 = nothing

    hp_function_map *filtered_functions;

    HashTable *trace_watch_callbacks;
    HashTable *trace_callbacks;
    HashTable *span_cache;

    uint32_t gc_runs; /* number of garbage collection runs */
    uint32_t gc_collected; /* number of collected items in garbage run */
    int compile_count;
    double compile_wt;
    uint64 cpu_start;
    int max_spans;
ZEND_END_MODULE_GLOBALS(hp)

#ifdef ZTS
#define TWG(v) TSRMG(hp_globals_id, zend_hp_globals *, v)
#else
#define TWG(v) (hp_globals.v)
#endif

PHP_MINIT_FUNCTION(algoweb);
PHP_MSHUTDOWN_FUNCTION(algoweb);
PHP_RINIT_FUNCTION(algoweb);
PHP_RSHUTDOWN_FUNCTION(algoweb);
PHP_MINFO_FUNCTION(algoweb);
PHP_GINIT_FUNCTION(hp);
PHP_GSHUTDOWN_FUNCTION(hp);

PHP_FUNCTION(algoweb_enable);
PHP_FUNCTION(algoweb_disable);
PHP_FUNCTION(algoweb_transaction_name);
PHP_FUNCTION(algoweb_fatal_backtrace);
PHP_FUNCTION(algoweb_prepend_overwritten);
PHP_FUNCTION(algoweb_last_detected_exception);
PHP_FUNCTION(algoweb_last_fatal_error);
PHP_FUNCTION(algoweb_sql_minify);

PHP_FUNCTION(algoweb_span_create);
PHP_FUNCTION(algoweb_get_spans);
PHP_FUNCTION(algoweb_span_timer_start);
PHP_FUNCTION(algoweb_span_timer_stop);
PHP_FUNCTION(algoweb_span_annotate);
PHP_FUNCTION(algoweb_span_watch);
PHP_FUNCTION(algoweb_span_callback);

#endif  /* PHP_ALGOWEB_H */
